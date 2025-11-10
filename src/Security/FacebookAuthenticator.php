<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\FacebookUser;
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

class FacebookAuthenticator extends OAuth2Authenticator
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
        return $request->attributes->get('_route') === 'connect_facebook_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('facebook');
        $accessToken = $this->fetchAccessToken($client);
        /** @var FacebookUser $facebookUser */
        $facebookUser = $client->fetchUserFromToken($accessToken);

        // Utiliser l'ID Facebook comme identifiant si l'email n'est pas disponible
        $identifier = $facebookUser->getEmail() ?: $facebookUser->getId();

        return new SelfValidatingPassport(
            new UserBadge($identifier, function () use ($facebookUser) {
                return $this->getOrCreateUser($facebookUser);
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

    private function getOrCreateUser(FacebookUser $facebookUser): User
    {
        $facebookId = $facebookUser->getId();
        $email = $facebookUser->getEmail();

        // Chercher d'abord par Facebook ID
        $existingUser = $this->userRepository->findOneBy(['facebookId' => $facebookId]);
        if ($existingUser instanceof User) {
            // Update avatar if user logs in with Facebook again
            $avatar = $facebookUser->getPictureUrl();
            if ($avatar && $existingUser->getAvatar() !== $avatar) {
                $existingUser->setAvatar($avatar);
                $this->entityManager->flush();
            }
            return $existingUser;
        }

        // Si l'email est disponible, chercher par email
        if ($email) {
            $userByEmail = $this->userRepository->findOneBy(['email' => $email]);
            if ($userByEmail instanceof User) {
                $userByEmail->setFacebookId($facebookId);
                // Set Facebook avatar when linking account
                $avatar = $facebookUser->getPictureUrl();
                if ($avatar) {
                    $userByEmail->setAvatar($avatar);
                }
                $this->entityManager->flush();
                return $userByEmail;
            }
        }

        $user = new User();
        
        // Si pas d'email, générer un email basé sur l'ID Facebook
        if (!$email) {
            $email = 'facebook_' . $facebookId . '@noemail.local';
        }
        
        $user->setEmail($email);
        $user->setFacebookId($facebookId);

        $fullName = $facebookUser->getName();
        $firstName = $facebookUser->getFirstName() ?: $fullName;
        $lastName = $facebookUser->getLastName() ?: $fullName;

        if (!$firstName || !$lastName) {
            [$firstName, $lastName] = $this->splitName($fullName);
        }

        $user->setPrenom($firstName ?: 'Utilisateur');
        $user->setNom($lastName ?: 'Facebook');

        // Get Facebook profile picture
        $avatar = $facebookUser->getPictureUrl();
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
            return ['Utilisateur', 'Facebook'];
        }

        $parts = preg_split('/\s+/', trim($fullName));
        $firstName = array_shift($parts) ?? 'Utilisateur';
        $lastName = $parts ? implode(' ', $parts) : 'Facebook';

        return [$firstName, $lastName];
    }
}
