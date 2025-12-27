<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAvailable();
        
        return $this->render('home/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request, EntityManagerInterface $em): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associer l'utilisateur connecté si existant
            if ($this->getUser()) {
                $reservation->setUser($this->getUser());
            }

            $reservation->setStatut('en_attente');
            $em->persist($reservation);
            $em->flush();

            $this->addFlash('success', '✅ Votre réservation a été enregistrée ! Nous vous confirmerons par email.');

            // Rediriger vers la page des réservations si connecté, sinon rester sur contact
            if ($this->getUser()) {
                return $this->redirectToRoute('app_profile_reservations');
            }

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('home/contact.html.twig', [
            'reservationForm' => $form,
        ]);
    }
}
