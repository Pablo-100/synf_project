<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Service pour gérer le panier via un Token (Cookie)
 * Le panier est stocké côté client pour éviter la charge DB et Session
 */
class CartService
{
    private const CART_COOKIE_NAME = 'freshmarket_cart_token';
    private const COOKIE_LIFETIME = 60 * 60 * 24 * 30; // 30 jours
    
    private array $cart = [];

    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository
    ) {
        $this->loadCartFromCookie();
    }
    
    /**
     * Charge le panier depuis le cookie du token
     */
    private function loadCartFromCookie(): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return;
        }

        $token = $request->cookies->get(self::CART_COOKIE_NAME);
        if ($token) {
            $decoded = json_decode(base64_decode($token), true);
            if (is_array($decoded)) {
                $this->cart = $decoded;
            }
        }
    }

    /**
     * Sauvegarde le panier dans un cookie (via la session temporairement pour le partage entre services)
     */
    private function persistCart(): void
    {
        $session = $this->requestStack->getSession();
        $session->set(self::CART_COOKIE_NAME, $this->cart);
        // Le cookie sera réellement envoyé par un EventSubscriber ou le contrôleur
    }
    
    public function addProduct(int $productId, int $quantity = 1): bool
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId] += $quantity;
        } else {
            $this->cart[$productId] = $quantity;
        }
        
        $this->persistCart();
        return true;
    }
    
    public function removeProduct(int $productId): void
    {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
            $this->persistCart();
        }
    }
    
    public function updateQuantity(int $productId, int $quantity): void
    {
        if ($quantity < 1) {
            $this->removeProduct($productId);
            return;
        }
        
        if (isset($this->cart[$productId])) {
            $this->cart[$productId] = $quantity;
            $this->persistCart();
        }
    }
    
    public function getCart(): array
    {
        $cartWithProducts = [];
        foreach ($this->cart as $productId => $quantity) {
            $product = $this->productRepository->find($productId);
            
            if ($product && $product->getStock() > 0) {
                $cartWithProducts[$productId] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            } else {
                $this->removeProduct($productId);
            }
        }
        
        return $cartWithProducts;
    }
    
    public function getRawCart(): array
    {
        return $this->cart;
    }
    
    public function getCount(): int
    {
        return count($this->cart);
    }
    
    public function getTotal(): float
    {
        $cart = $this->getCart();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['product']->getPrix() * $item['quantity'];
        }
        return $total;
    }
    
    public function getTotalQuantity(): int
    {
        return array_sum($this->cart);
    }
    
    public function clear(): void
    {
        $this->cart = [];
        $this->persistCart();
    }
    
    public function isEmpty(): bool
    {
        return empty($this->cart);
    }
    
    public function hasProduct(int $productId): bool
    {
        return isset($this->cart[$productId]);
    }

    /**
     * Génère l'objet Cookie pour la réponse (appelé par l'EventSubscriber)
     */
    public function createCookie(): Cookie
    {
        $encoded = base64_encode(json_encode($this->cart));
        return Cookie::create(
            self::CART_COOKIE_NAME,
            $encoded,
            time() + self::COOKIE_LIFETIME,
            '/',
            null,
            false, // secure (mettez à true en production HTTPS)
            true   // httpOnly
        );
    }
}
