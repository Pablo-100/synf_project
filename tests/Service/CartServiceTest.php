<?php

namespace App\Tests\Service;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartServiceTest extends KernelTestCase
{
    private CartService $cartService;
    private Session $session;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->session = new Session(new MockArraySessionStorage());
        $this->cartService = new CartService($this->session);
    }

    public function testAddToCart(): void
    {
        // Arrange
        $productId = 1;
        $quantity = 2;

        // Act
        $this->cartService->add($productId, $quantity);
        $cart = $this->cartService->getCart();

        // Assert
        $this->assertArrayHasKey($productId, $cart);
        $this->assertEquals($quantity, $cart[$productId]);
    }

    public function testRemoveFromCart(): void
    {
        // Arrange
        $productId = 1;
        $this->cartService->add($productId, 2);

        // Act
        $this->cartService->remove($productId);
        $cart = $this->cartService->getCart();

        // Assert
        $this->assertArrayNotHasKey($productId, $cart);
    }

    public function testUpdateQuantity(): void
    {
        // Arrange
        $productId = 1;
        $this->cartService->add($productId, 2);

        // Act
        $this->cartService->updateQuantity($productId, 5);
        $cart = $this->cartService->getCart();

        // Assert
        $this->assertEquals(5, $cart[$productId]);
    }

    public function testClearCart(): void
    {
        // Arrange
        $this->cartService->add(1, 2);
        $this->cartService->add(2, 3);

        // Act
        $this->cartService->clear();
        $cart = $this->cartService->getCart();

        // Assert
        $this->assertEmpty($cart);
    }

    public function testGetTotal(): void
    {
        // Arrange
        $this->cartService->add(1, 2);
        $this->cartService->add(2, 3);

        // Act
        $total = $this->cartService->getTotal();

        // Assert
        $this->assertIsInt($total);
        $this->assertEquals(5, $total); // 2 + 3
    }

    public function testIsEmpty(): void
    {
        // Test panier vide
        $this->assertTrue($this->cartService->isEmpty());

        // Ajouter un article
        $this->cartService->add(1, 1);
        $this->assertFalse($this->cartService->isEmpty());

        // Vider le panier
        $this->cartService->clear();
        $this->assertTrue($this->cartService->isEmpty());
    }
}
