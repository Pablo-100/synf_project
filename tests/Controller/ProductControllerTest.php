<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testProductIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/products');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Produits');
    }

    public function testProductSearch(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/products/search?q=pomme');

        $this->assertResponseIsSuccessful();
    }

    public function testProductShowWithValidId(): void
    {
        $client = static::createClient();
        
        // Test avec un ID valide (supposant qu'il existe)
        $client->request('GET', '/products/1');
        
        // Accepte 200 (succès) ou 404 (produit non trouvé selon données)
        $this->assertResponseStatusCodeSame(200);
    }

    public function testProductShowWithInvalidId(): void
    {
        $client = static::createClient();
        $client->request('GET', '/products/99999');

        $this->assertResponseStatusCodeSame(404);
    }
}
