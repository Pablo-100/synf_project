<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/add/{id}', name: 'app_cart_add', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function add(int $id, ProductRepository $productRepository, CartService $cartService): Response
    {
        $product = $productRepository->find($id);
        
        if (!$product) {
            $this->addFlash('error', 'Produit introuvable.');
            return $this->redirectToRoute('app_product_index');
        }
        
        if ($product->getStock() <= 0) {
            $this->addFlash('error', 'Ce produit est en rupture de stock.');
            return $this->redirectToRoute('app_product_show', ['id' => $id]);
        }
        
        // Ajouter le produit au panier (stockage optimisé: ID seulement)
        $cartService->addProduct($id);
        
        $this->addFlash('success', 'Produit ajouté au panier avec succès !');
        
        return $this->redirectToRoute('app_product_show', ['id' => $id]);
    }

    #[Route('/add-ajax/{id}', name: 'app_cart_add_ajax', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addAjax(int $id, ProductRepository $productRepository, CartService $cartService, Request $request): Response
    {
        // Vérifier que c'est une requête AJAX
        if (!$request->isXmlHttpRequest()) {
            return $this->json(['success' => false, 'message' => 'Requête invalide'], 400);
        }

        $product = $productRepository->find($id);
        
        if (!$product) {
            return $this->json(['success' => false, 'message' => 'Produit introuvable']);
        }
        
        if ($product->getStock() <= 0) {
            return $this->json(['success' => false, 'message' => 'Ce produit est en rupture de stock']);
        }
        
        // Ajouter le produit au panier (stockage optimisé)
        $cartService->addProduct($id);
        
        // Retourner le nombre de produits différents
        $cartCount = $cartService->getCount();
        
        return $this->json([
            'success' => true,
            'message' => 'Produit ajouté au panier',
            'cartCount' => $cartCount
        ]);
    }
    
    #[Route('/', name: 'app_cart_index')]
    #[IsGranted('ROLE_USER')]
    public function index(CartService $cartService): Response
    {
        $cart = $cartService->getCart();
        $total = $cartService->getTotal();
        
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }
    
    #[Route('/remove/{id}', name: 'app_cart_remove', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function remove(int $id, CartService $cartService): Response
    {
        $cartService->removeProduct($id);
        $this->addFlash('success', 'Produit retiré du panier.');
        
        return $this->redirectToRoute('app_cart_index');
    }
    
    #[Route('/update/{id}', name: 'app_cart_update', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function update(int $id, Request $request, CartService $cartService): Response
    {
        $quantity = (int) $request->request->get('quantity', 1);
        
        if ($quantity < 1) {
            $this->addFlash('error', 'La quantité doit être au moins 1.');
            return $this->redirectToRoute('app_cart_index');
        }
        
        $cartService->updateQuantity($id, $quantity);
        $this->addFlash('success', 'Quantité mise à jour.');
        
        return $this->redirectToRoute('app_cart_index');
    }
    
    #[Route('/clear', name: 'app_cart_clear')]
    #[IsGranted('ROLE_USER')]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();
        $this->addFlash('success', 'Panier vidé.');
        
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/checkout', name: 'app_cart_checkout')]
    #[IsGranted('ROLE_USER')]
    public function checkout(CartService $cartService): Response
    {
        if ($cartService->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart_index');
        }
        
        $cart = $cartService->getCart();
        $total = $cartService->getTotal();
        
        return $this->render('cart/checkout.html.twig', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    #[Route('/count', name: 'app_cart_count', methods: ['GET'])]
    public function count(CartService $cartService): Response
    {
        // Retourne le nombre de produits différents
        $count = $cartService->getCount();
        
        return $this->json(['count' => $count]);
    }

    #[Route('/place-order', name: 'app_cart_place_order', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function placeOrder(Request $request, CartService $cartService, EntityManagerInterface $em): Response
    {
        if ($cartService->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart_index');
        }
        
        $cart = $cartService->getCart();
        
        // Vérifier le token CSRF
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('place-order', $token)) {
            $this->addFlash('error', 'Erreur de sécurité. Veuillez réessayer.');
            return $this->redirectToRoute('app_cart_checkout');
        }
        
        // Créer la commande
        $order = new Order();
        $order->setUser($this->getUser());
        $order->setStatut('en_attente');
        
        // Récupérer l'adresse de livraison depuis le formulaire (optionnel)
        $adresse = $request->request->get('adresse_livraison');
        if ($adresse) {
            $order->setAdresseLivraison($adresse);
        }
        
        // Créer les OrderItems
        $total = 0;
        foreach ($cart as $productId => $item) {
            $product = $item['product'];
            
            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantite($item['quantity']);
            $orderItem->setPrixUnitaire($product->getPrix());
            
            $order->addOrderItem($orderItem);
            
            $total += $product->getPrix() * $item['quantity'];
        }
        
        $order->setMontantTotal((string)$total);
        
        // Persister la commande (les OrderItems seront persistés automatiquement grâce à cascade)
        $em->persist($order);
        $em->flush();
        
        // Vider le panier
        $cartService->clear();
        
        $this->addFlash('success', sprintf('Votre commande #%s a été enregistrée avec succès !', $order->getNumeroCommande()));
        
        return $this->redirectToRoute('app_profile_orders');
    }
}
