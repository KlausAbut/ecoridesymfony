<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Participation;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('default/acceuil.html.twig');
    }

    #[Route('/mes-reservations', name: 'user_reservations')]
    public function mesReservations(): Response
    {
    $user = $this->getUser();
    $participations = $user->getParticipations();

    return $this->render('user/reservations.html.twig', [
        'participations' => $participations
    ]);
    }

    #[Route('/annuler-reservation/{id}', name: 'annuler_reservation', methods: ['POST'])]
    public function annulerReservation(Participation $participation, EntityManagerInterface $em): RedirectResponse
    {
    $user = $this->getUser();

    // Vérifie que la réservation appartient bien à l'utilisateur connecté
    if ($participation->getUser() !== $user) {
        throw $this->createAccessDeniedException();
    }

    // Récupération du trajet lié
    $covoiturage = $participation->getCovoiturage();

    // Rendre la place
    $covoiturage->setNbPlace($covoiturage->getNbPlace() + 1);

    // Supprimer la participation
    $em->remove($participation);
    $em->flush();

    $this->addFlash('success', 'Votre réservation a été annulée.');
    return $this->redirectToRoute('user_reservations');
    }
}