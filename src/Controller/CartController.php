<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/add/{id}', name: 'app_cart_add', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function add(int $id, ProductRepository $productRepository, SessionInterface $session): Response
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
        
        // Récupérer le panier de la session
        $cart = $session->get('cart', []);
        
        // Ajouter le produit ou augmenter la quantité
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'product' => $product,
                'quantity' => 1
            ];
        }
        
        // Sauvegarder le panier dans la session
        $session->set('cart', $cart);
        
        $this->addFlash('success', 'Produit ajouté au panier avec succès !');
        
        return $this->redirectToRoute('app_product_show', ['id' => $id]);
    }
    
    #[Route('/', name: 'app_cart_index')]
    #[IsGranted('ROLE_USER')]
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['product']->getPrix() * $item['quantity'];
        }
        
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }
    
    #[Route('/remove/{id}', name: 'app_cart_remove', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function remove(int $id, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $session->set('cart', $cart);
            $this->addFlash('success', 'Produit retiré du panier.');
        }
        
        return $this->redirectToRoute('app_cart_index');
    }
    
    #[Route('/update/{id}', name: 'app_cart_update', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function update(int $id, Request $request, SessionInterface $session): Response
    {
        $quantity = (int) $request->request->get('quantity', 1);
        
        if ($quantity < 1) {
            $this->addFlash('error', 'La quantité doit être au moins 1.');
            return $this->redirectToRoute('app_cart_index');
        }
        
        $cart = $session->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            $session->set('cart', $cart);
            $this->addFlash('success', 'Quantité mise à jour.');
        }
        
        return $this->redirectToRoute('app_cart_index');
    }
    
    #[Route('/clear', name: 'app_cart_clear')]
    #[IsGranted('ROLE_USER')]
    public function clear(SessionInterface $session): Response
    {
        $session->remove('cart');
        $this->addFlash('success', 'Panier vidé.');
        
        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/checkout', name: 'app_cart_checkout')]
    #[IsGranted('ROLE_USER')]
    public function checkout(SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        
        if (empty($cart)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart_index');
        }
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['product']->getPrix() * $item['quantity'];
        }
        
        return $this->render('cart/checkout.html.twig', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    #[Route('/place-order', name: 'app_cart_place_order', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function placeOrder(Request $request, SessionInterface $session, EntityManagerInterface $em, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);
        
        if (empty($cart)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart_index');
        }
        
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
            // Récupérer le produit depuis la base de données (pas depuis la session)
            $product = $productRepository->find($productId);
            
            if (!$product) {
                continue; // Ignorer les produits qui n'existent plus
            }
            
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
        $session->remove('cart');
        
        $this->addFlash('success', sprintf('Votre commande #%s a été enregistrée avec succès !', $order->getNumeroCommande()));
        
        return $this->redirectToRoute('app_profile_orders');
    }
}
