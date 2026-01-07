<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityTest extends WebTestCase
{
    public function testLoginPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Connexion');
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/login');
        
        $client->followRedirect();
        $this->assertSelectorExists('.alert-danger');
    }

    public function testRegistrationPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Inscription');
    }

    public function testAdminAreaRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin/dashboard');

        $this->assertResponseRedirects('/login');
    }

    public function testCSRFProtectionOnForms(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        // Vérifier la présence du token CSRF
        $this->assertSelectorExists('input[name="_csrf_token"]');
    }
}
