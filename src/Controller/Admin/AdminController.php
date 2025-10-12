<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function dashboard(
        UserRepository $userRepository,
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        ReservationRepository $reservationRepository
    ): Response {
        // Statistiques générales
        $stats = [
            'total_users' => count($userRepository->findAll()),
            'total_products' => count($productRepository->findAll()),
            'total_orders' => count($orderRepository->findAll()),
            'total_reservations' => count($reservationRepository->findAll()),
            'total_revenue' => $orderRepository->getTotalRevenue(),
            'pending_reservations' => $reservationRepository->countByStatus('en_attente'),
            'available_products' => count($productRepository->findAvailable()),
        ];

        // Top produits
        $topProducts = $productRepository->findBy([], ['nom' => 'ASC'], 5);
        
        // Dernières commandes
        $recentOrders = $orderRepository->findRecentOrders(5);
        
        // Dernières réservations
        $recentReservations = $reservationRepository->findBy([], ['dateReservation' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats,
            'top_products' => $topProducts,
            'recent_orders' => $recentOrders,
            'recent_reservations' => $recentReservations,
        ]);
    }

    #[Route('/products', name: 'app_admin_products')]
    public function products(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['nom' => 'ASC']);

        return $this->render('admin/products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/new', name: 'app_admin_product_new')]
    public function newProduct(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $product = new \App\Entity\Product();
        $form = $this->createForm(\App\Form\ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de l'image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $product->setImage($imageFileName);
            }

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit créé avec succès.');
            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/product_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/products/{id}/edit', name: 'app_admin_product_edit')]
    public function editProduct(int $id, Request $request, ProductRepository $productRepository, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            $this->addFlash('error', 'Produit introuvable.');
            return $this->redirectToRoute('app_admin_products');
        }

        $form = $this->createForm(\App\Form\ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de la nouvelle image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                // Supprimer l'ancienne image si elle existe
                if ($product->getImage()) {
                    $fileUploader->delete($product->getImage());
                }
                
                $imageFileName = $fileUploader->upload($imageFile);
                $product->setImage($imageFileName);
            }

            $product->setUpdatedAt(new \DateTime());
            $em->flush();

            $this->addFlash('success', 'Produit mis à jour.');
            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/product_edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

    #[Route('/products/{id}/delete', name: 'app_admin_product_delete', methods: ['POST'])]
    public function deleteProduct(int $id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            $this->addFlash('error', 'Produit introuvable.');
            return $this->redirectToRoute('app_admin_products');
        }

        // Basic CSRF protection (token name: delete-product-{id})
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete-product-'.$id, $token)) {
            $this->addFlash('error', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('app_admin_products');
        }

        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Produit supprimé.');
        return $this->redirectToRoute('app_admin_products');
    }

    #[Route('/orders', name: 'app_admin_orders')]
    public function orders(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/orders/new', name: 'app_admin_order_new')]
    public function newOrder(Request $request, EntityManagerInterface $em): Response
    {
        $order = new \App\Entity\Order();
        $form = $this->createForm(\App\Form\OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($order);
            $em->flush();

            $this->addFlash('success', 'Commande créée avec succès.');
            return $this->redirectToRoute('app_admin_orders');
        }

        return $this->render('admin/order_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/orders/{id}/edit', name: 'app_admin_order_edit')]
    public function editOrder(int $id, Request $request, OrderRepository $orderRepository, EntityManagerInterface $em): Response
    {
        $order = $orderRepository->find($id);
        if (!$order) {
            $this->addFlash('error', 'Commande introuvable.');
            return $this->redirectToRoute('app_admin_orders');
        }

        $form = $this->createForm(\App\Form\OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Commande mise à jour.');
            return $this->redirectToRoute('app_admin_orders');
        }

        return $this->render('admin/order_edit.html.twig', [
            'form' => $form->createView(),
            'order' => $order,
        ]);
    }

    #[Route('/orders/{id}/delete', name: 'app_admin_order_delete', methods: ['POST'])]
    public function deleteOrder(int $id, OrderRepository $orderRepository, Request $request, EntityManagerInterface $em): Response
    {
        $order = $orderRepository->find($id);
        if (!$order) {
            $this->addFlash('error', 'Commande introuvable.');
            return $this->redirectToRoute('app_admin_orders');
        }

        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete-order-'.$id, $token)) {
            $this->addFlash('error', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('app_admin_orders');
        }

        $em->remove($order);
        $em->flush();

        $this->addFlash('success', 'Commande supprimée.');
        return $this->redirectToRoute('app_admin_orders');
    }

    #[Route('/reservations', name: 'app_admin_reservations')]
    public function reservations(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findBy([], ['dateReservation' => 'DESC']);

        return $this->render('admin/reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/orders/{id}/status/{status}', name: 'app_admin_order_status', methods: ['POST'])]
    public function changeOrderStatus(int $id, string $status, OrderRepository $orderRepository, EntityManagerInterface $em): Response
    {
        $order = $orderRepository->find($id);
        
        if (!$order) {
            $this->addFlash('error', 'Commande introuvable.');
            return $this->redirectToRoute('app_admin_orders');
        }

        $validStatuses = ['validee', 'en_preparation', 'livree', 'annulee'];
        if (!in_array($status, $validStatuses)) {
            $this->addFlash('error', 'Statut invalide.');
            return $this->redirectToRoute('app_admin_orders');
        }

        $order->setStatut($status);
        $em->flush();

        $statusLabels = [
            'validee' => 'validée',
            'en_preparation' => 'mise en préparation',
            'livree' => 'marquée comme livrée',
            'annulee' => 'annulée'
        ];

        $this->addFlash('success', sprintf('La commande #%s a été %s.', $order->getNumeroCommande(), $statusLabels[$status]));

        return $this->redirectToRoute('app_admin_orders');
    }

    #[Route('/reservations/{id}/status/{status}', name: 'app_admin_reservation_status', methods: ['POST'])]
    public function changeReservationStatus(int $id, string $status, ReservationRepository $reservationRepository, EntityManagerInterface $em): Response
    {
        $reservation = $reservationRepository->find($id);
        
        if (!$reservation) {
            $this->addFlash('error', 'Réservation introuvable.');
            return $this->redirectToRoute('app_admin_reservations');
        }

        $validStatuses = ['confirmee', 'annulee'];
        if (!in_array($status, $validStatuses)) {
            $this->addFlash('error', 'Statut invalide.');
            return $this->redirectToRoute('app_admin_reservations');
        }

        $reservation->setStatut($status);
        $em->flush();

        $statusLabels = [
            'confirmee' => 'confirmée',
            'annulee' => 'annulée'
        ];

        $this->addFlash('success', sprintf('La réservation a été %s.', $statusLabels[$status]));

        return $this->redirectToRoute('app_admin_reservations');
    }
}
