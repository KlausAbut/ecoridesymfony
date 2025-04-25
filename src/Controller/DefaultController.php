<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Participation;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CovoiturageRepository;
use App\Repository\AvisRepository;
use App\Repository\VoitureRepository;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, CovoiturageRepository $repo, AvisRepository $avisRepo): Response
    {
        $resultats = [];

        if (
            $request->query->get('depart') &&
            $request->query->get('arrivee') &&
            $request->query->get('date')
        ) {
            $resultats = $repo->rechercherTrajets(
                $request->query->get('depart'),
                $request->query->get('arrivee'),
                new \DateTime($request->query->get('date'))
            );
        }

        // récupère les avis validés
        $avisList = $avisRepo->findBy(['statut' => 'VALIDÉ'], ['id' => 'DESC'], 10);

        // rend la bonne vue avec les deux variables
        return $this->render('default/accueil.html.twig', [
            'resultats' => $resultats,
            'avisList' => $avisList
        ]);
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

    #[Route('/mes-trajets', name: 'user_trajets')]
    public function mesTrajets(): Response
    {
        $user = $this->getUser();
        $covoiturages = $user->getCovoituragesCrees(); // méthode à ajouter dans User

        return $this->render('user/trajets.html.twig', [
            'covoiturages' => $covoiturages
        ]);
    }

    #[Route('/profil', name: 'user_profile')]
    public function profil(VoitureRepository $voitureRepo, CovoiturageRepository $covoiturageRepo): Response
    {
        $user = $this->getUser();

        return $this->render('user/profil.html.twig', [
            'user' => $user,
            'voitures' => $voitureRepo->findBy(['user' => $user]),
            'covoiturages' => $covoiturageRepo->findBy(['createdBy' => $user])
        ]);
    }


    #[Route('/devenir-conducteur', name: 'user_become_conducteur')]
    public function devenirConducteur(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $user->setIsConducteur(true);
        $em->flush();

        $this->addFlash('success', 'Vous êtes maintenant conducteur. Vous pouvez créer votre voiture et proposer des trajets.');

        return $this->redirectToRoute('user_profile');
    }

}