<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use App\Repository\OrderRepository;
use App\Repository\ReservationRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(OrderRepository $orderRepository, ReservationRepository $reservationRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        // Statistiques de l'utilisateur
        $orders = $orderRepository->findBy(['user' => $user]);
        $reservations = $reservationRepository->findBy(['user' => $user]);
        
        $stats = [
            'total_orders' => count($orders),
            'total_reservations' => count($reservations),
            'pending_reservations' => $reservationRepository->countPendingByUser($user),
            'total_spent' => array_sum(array_map(fn($order) => $order->getMontantTotal(), $orders)),
        ];
        
        // Dernières commandes
        $recentOrders = $orderRepository->findBy(['user' => $user], ['createdAt' => 'DESC'], 3);
        
        // Prochaines réservations (seulement celles à venir, non annulées)
        $upcomingReservations = $reservationRepository->findUpcomingByUser($user, 3);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'stats' => $stats,
            'recent_orders' => $recentOrders,
            'upcoming_reservations' => $upcomingReservations,
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, FileUploader $fileUploader): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de l'avatar
            $avatarFile = $form->get('avatarFile')->getData();
            if ($avatarFile) {
                // Supprimer l'ancien avatar si il existe
                if ($user->getAvatar()) {
                    $fileUploader->delete($user->getAvatar());
                }
                
                $avatarFileName = $fileUploader->upload($avatarFile);
                $user->setAvatar($avatarFileName);
            }

            // Si un nouveau mot de passe est fourni
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $plainPassword)
                );
            }

            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'profileForm' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/reservations', name: 'app_profile_reservations')]
    public function reservations(ReservationRepository $reservationRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $reservations = $reservationRepository->findBy(
            ['user' => $user],
            ['dateReservation' => 'DESC']
        );

        return $this->render('profile/reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/orders', name: 'app_profile_orders')]
    public function orders(OrderRepository $orderRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $orders = $orderRepository->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );

        return $this->render('profile/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/statistics', name: 'app_profile_statistics')]
    public function statistics(): Response
    {
        return $this->render('profile/statistics.html.twig');
    }

    #[Route('/statistics/api/orders', name: 'app_profile_statistics_orders', methods: ['GET'])]
    public function getOrdersStatistics(OrderRepository $orderRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = $orderRepository->getOrdersByUser($user->getId());
        
        $labels = [];
        $counts = [];
        $totals = [];
        
        foreach ($data as $item) {
            $monthNames = [
                1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
                5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Août',
                9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
            ];
            $labels[] = $monthNames[$item['month']] . ' ' . $item['year'];
            $counts[] = (int) $item['count'];
            $totals[] = (float) $item['total'];
        }
        
        return $this->json([
            'labels' => $labels,
            'counts' => $counts,
            'totals' => $totals,
        ]);
    }

    #[Route('/statistics/api/reservations', name: 'app_profile_statistics_reservations', methods: ['GET'])]
    public function getReservationsStatistics(ReservationRepository $reservationRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = $reservationRepository->getReservationsByUser($user->getId());
        
        $labels = [];
        $counts = [];
        
        foreach ($data as $item) {
            $monthNames = [
                1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
                5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Août',
                9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
            ];
            $labels[] = $monthNames[$item['month']] . ' ' . $item['year'];
            $counts[] = (int) $item['count'];
        }
        
        return $this->json([
            'labels' => $labels,
            'counts' => $counts,
        ]);
    }

    #[Route('/api/orders-by-status', name: 'app_profile_orders_by_status', methods: ['GET'])]
    public function getOrdersByStatus(OrderRepository $orderRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $orders = $orderRepository->findBy(['user' => $user]);
        
        $statusCounts = [
            'en_attente' => 0,
            'validee' => 0,
            'en_preparation' => 0,
            'livree' => 0,
            'annulee' => 0,
        ];
        
        foreach ($orders as $order) {
            $status = $order->getStatut();
            if (isset($statusCounts[$status])) {
                $statusCounts[$status]++;
            }
        }
        
        return $this->json([
            'labels' => ['En attente', 'Validée', 'En préparation', 'Livrée', 'Annulée'],
            'data' => array_values($statusCounts),
        ]);
    }

    #[Route('/api/reservations-by-status', name: 'app_profile_reservations_by_status', methods: ['GET'])]
    public function getReservationsByStatus(ReservationRepository $reservationRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $reservations = $reservationRepository->findBy(['user' => $user]);
        
        $statusCounts = [
            'en_attente' => 0,
            'confirmee' => 0,
            'annulee' => 0,
            'terminee' => 0,
        ];
        
        foreach ($reservations as $reservation) {
            $status = $reservation->getStatut();
            if (isset($statusCounts[$status])) {
                $statusCounts[$status]++;
            }
        }
        
        return $this->json([
            'labels' => ['En attente', 'Confirmée', 'Annulée', 'Terminée'],
            'data' => array_values($statusCounts),
        ]);
    }
}
