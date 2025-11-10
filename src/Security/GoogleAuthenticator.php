<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class GoogleAuthenticator extends OAuth2Authenticator
{
    use TargetPathTrait;

    public function __construct(
        private ClientRegistry $clientRegistry,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);
        /** @var GoogleUser $googleUser */
        $googleUser = $client->fetchUserFromToken($accessToken);

        return new SelfValidatingPassport(
            new UserBadge($googleUser->getEmail(), function () use ($googleUser) {
                return $this->getOrCreateUser($googleUser);
            }),
            [new RememberMeBadge()]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_login', ['oauth_error' => 1]));
    }

    private function getOrCreateUser(GoogleUser $googleUser): User
    {
        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();

        if (!$email) {
            throw new AuthenticationException('Votre compte Google n\'a pas d\'email public.');
        }

        $existingUser = $this->userRepository->findOneBy(['googleId' => $googleId]);
        if ($existingUser instanceof User) {
            // Update avatar if user logs in with Google again
            $avatar = $googleUser->getAvatar();
            if ($avatar && $existingUser->getAvatar() !== $avatar) {
                $existingUser->setAvatar($avatar);
                $this->entityManager->flush();
            }
            return $existingUser;
        }

        $userByEmail = $this->userRepository->findOneBy(['email' => $email]);
        if ($userByEmail instanceof User) {
            $userByEmail->setGoogleId($googleId);
            // Set Google avatar when linking account
            $avatar = $googleUser->getAvatar();
            if ($avatar) {
                $userByEmail->setAvatar($avatar);
            }
            $this->entityManager->flush();
            return $userByEmail;
        }

        $user = new User();
        $user->setEmail($email);
        $user->setGoogleId($googleId);

        $fullName = $googleUser->getName();
        $firstName = $googleUser->getFirstName() ?: $fullName;
        $lastName = $googleUser->getLastName() ?: $fullName;

        if (!$firstName || !$lastName) {
            [$firstName, $lastName] = $this->splitName($fullName);
        }

        $user->setPrenom($firstName ?: 'Utilisateur');
        $user->setNom($lastName ?: 'Google');

        // Get Google profile picture
        $avatar = $googleUser->getAvatar();
        if ($avatar) {
            $user->setAvatar($avatar);
        }

        $randomPassword = bin2hex(random_bytes(16));
        $user->setPassword($this->passwordHasher->hashPassword($user, $randomPassword));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function splitName(?string $fullName): array
    {
        if (!$fullName) {
            return ['Utilisateur', 'Google'];
        }

        $parts = preg_split('/\s+/', trim($fullName));
        $firstName = array_shift($parts) ?? 'Utilisateur';
        $lastName = $parts ? implode(' ', $parts) : 'Google';

        return [$firstName, $lastName];
    }
}
