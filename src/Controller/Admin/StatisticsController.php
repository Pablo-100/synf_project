<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/statistics')]
#[IsGranted('ROLE_ADMIN')]
class StatisticsController extends AbstractController
{
    #[Route('/', name: 'app_admin_statistics')]
    public function index(): Response
    {
        return $this->render('admin/statistics.html.twig');
    }

    #[Route('/api/orders-by-month', name: 'app_admin_statistics_orders_month', methods: ['GET'])]
    public function getOrdersByMonth(OrderRepository $orderRepository): JsonResponse
    {
        $data = $orderRepository->getOrdersByMonth();
        
        $labels = [];
        $counts = [];
        $revenues = [];
        
        foreach ($data as $item) {
            $labels[] = $this->getMonthName($item['month']) . ' ' . $item['year'];
            $counts[] = (int) $item['count'];
            $revenues[] = (float) $item['revenue'];
        }
        
        return $this->json([
            'labels' => $labels,
            'counts' => $counts,
            'revenues' => $revenues,
        ]);
    }

    #[Route('/api/orders-by-status', name: 'app_admin_statistics_orders_status', methods: ['GET'])]
    public function getOrdersByStatus(OrderRepository $orderRepository): JsonResponse
    {
        $data = $orderRepository->getOrdersByStatus();
        
        $statusOrder = ['en_attente', 'validee', 'en_preparation', 'livree', 'annulee'];
        $statusLabels = [
            'en_attente' => 'En attente',
            'validee' => 'Validée',
            'en_preparation' => 'En préparation',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
        ];
        
        // Create a map of status counts
        $statusMap = [];
        foreach ($data as $item) {
            $statusMap[$item['statut']] = (int) $item['count'];
        }
        
        // Build arrays in the correct order
        $labels = [];
        $counts = [];
        foreach ($statusOrder as $status) {
            $labels[] = $statusLabels[$status];
            $counts[] = $statusMap[$status] ?? 0;
        }
        
        return $this->json([
            'labels' => $labels,
            'data' => $counts,
        ]);
    }

    #[Route('/api/reservations-by-month', name: 'app_admin_statistics_reservations_month', methods: ['GET'])]
    public function getReservationsByMonth(ReservationRepository $reservationRepository): JsonResponse
    {
        $data = $reservationRepository->getReservationsByMonth();
        
        $labels = [];
        $counts = [];
        
        foreach ($data as $item) {
            $labels[] = $this->getMonthName($item['month']) . ' ' . $item['year'];
            $counts[] = (int) $item['count'];
        }
        
        return $this->json([
            'labels' => $labels,
            'counts' => $counts,
        ]);
    }

    #[Route('/api/reservations-by-status', name: 'app_admin_statistics_reservations_status', methods: ['GET'])]
    public function getReservationsByStatus(ReservationRepository $reservationRepository): JsonResponse
    {
        $data = $reservationRepository->getReservationsByStatus();
        
        $statusOrder = ['en_attente', 'confirmee', 'annulee', 'terminee'];
        $statusLabels = [
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'annulee' => 'Annulée',
            'terminee' => 'Terminée',
        ];
        
        // Create a map of status counts
        $statusMap = [];
        foreach ($data as $item) {
            $statusMap[$item['statut']] = (int) $item['count'];
        }
        
        // Build arrays in the correct order
        $labels = [];
        $counts = [];
        foreach ($statusOrder as $status) {
            $labels[] = $statusLabels[$status];
            $counts[] = $statusMap[$status] ?? 0;
        }
        
        return $this->json([
            'labels' => $labels,
            'data' => $counts,
        ]);
    }

    #[Route('/api/users-growth', name: 'app_admin_statistics_users_growth', methods: ['GET'])]
    public function getUsersGrowth(UserRepository $userRepository): JsonResponse
    {
        $data = $userRepository->getUsersGrowthByMonth();
        
        $labels = [];
        $counts = [];
        
        foreach ($data as $item) {
            $labels[] = $this->getMonthName($item['month']) . ' ' . $item['year'];
            $counts[] = (int) $item['count'];
        }
        
        return $this->json([
            'labels' => $labels,
            'counts' => $counts,
        ]);
    }

    private function getMonthName(int $month): string
    {
        $months = [
            1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Août',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'
        ];
        return $months[$month] ?? '';
    }

    private function getStatusLabel(string $status): string
    {
        return match($status) {
            'en_cours' => 'En cours',
            'validee' => 'Validée',
            'livree' => 'Livrée',
            'annulee' => 'Annulée',
            default => $status,
        };
    }

    private function getReservationStatusLabel(string $status): string
    {
        return match($status) {
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'annulee' => 'Annulée',
            'terminee' => 'Terminée',
            default => $status,
        };
    }
}
