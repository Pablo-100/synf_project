<?php

namespace App\EventSubscriber;

use App\Service\CartService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CartSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function onKernelResponse(ResponseEvent $event): void
    {
        // Ne pas injecter si ce n'est pas la requête principale
        if (!$event->isMainRequest()) {
            return;
        }

        $session = $event->getRequest()->getSession();
        
        // Si le panier a été modifié (stocké temporairement en session par CartService::persistCart)
        if ($session->has('freshmarket_cart_token')) {
            $response = $event->getResponse();
            $response->headers->setCookie($this->cartService->createCookie());
            
            // Nettoyer le flag de session pour ne pas renvoyer le cookie inutilement à chaque fois
            // Note: On peut aussi choisir de le renvoyer à chaque fois pour rafraîchir la date d'expiration
            $session->remove('freshmarket_cart_token');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
