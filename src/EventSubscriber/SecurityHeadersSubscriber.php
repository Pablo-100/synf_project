<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Ajoute des headers de sécurité à toutes les réponses HTTP
 * Protection contre XSS, Clickjacking, MIME sniffing, etc.
 */
class SecurityHeadersSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();
        $headers = $response->headers;

        // Protection XSS - Empêche le navigateur d'exécuter des scripts malveillants
        $headers->set('X-XSS-Protection', '1; mode=block');

        // Protection Clickjacking - Empêche l'inclusion de la page dans une iframe
        $headers->set('X-Frame-Options', 'DENY');

        // Protection MIME Sniffing - Force le navigateur à respecter le Content-Type
        $headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer Policy - Contrôle les informations envoyées dans le referrer
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy - Contrôle les fonctionnalités du navigateur
        $headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy - Protection XSS avancée
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
            "img-src 'self' data: https:",
            "font-src 'self' data: https://cdn.jsdelivr.net",
            "connect-src 'self'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);
        $headers->set('Content-Security-Policy', $csp);

        // Strict Transport Security (HTTPS uniquement en production)
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'prod') {
            $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
    }
}
