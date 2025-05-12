<?php

namespace App\Controller;

use App\Entity\Covoiturage;
use App\Entity\Participation;
use App\Form\CovoiturageType;
use App\Enum\CovoiturageStatut;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CovoiturageRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\UserCredit;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/covoiturage', name: 'covoiturage_')]
class CovoiturageController extends AbstractController
{
    #[Route('/', name:'list')]
    public function list(CovoiturageRepository $covoiturageRepository): Response
    {
        return $this->render('covoiturage/listco.html.twig', [
            'covoiturages' => $covoiturageRepository->findAll(),
        ]);
    }

    #[Route('/show/{id}', name:'show')]
    #[IsGranted('show','covoiturage')]
    public function show(Covoiturage $covoiturage = null): Response
    {
        return $this->render('covoiturage/showco.html.twig', [
            'covoiturage' => $covoiturage
        ]);

    }

    #[Route('/participer/{id}', name: 'covoiturage_participer', methods: ['POST'])]
    public function participer(Request $request, Covoiturage $covoiturage, EntityManagerInterface $em, DocumentManager $dm): RedirectResponse
    {
    $user = $this->getUser();

    if (!$user) {
        throw $this->createAccessDeniedException();
    }

    if (!$this->isCsrfTokenValid('participer' . $covoiturage->getId(), $request->request->get('_token'))) {
        throw $this->createAccessDeniedException('Jeton CSRF invalide');
    }

    $credit = $dm->getRepository(UserCredit::class)->findOneBy(['userId' => $user->getId()]);

    if (!$credit || $credit->getAmount() < 1) {
        $this->addFlash('danger', 'Vous n\'avez pas assez de crédits pour participer.');
        return $this->redirectToRoute('user_profile');
    }

    if ($covoiturage->getNbPlace() <= 0) {
        $this->addFlash('danger', 'Plus de place disponible pour ce trajet.');
        return $this->redirectToRoute('user_profile');
    }

    $covoiturage->setNbPlace($covoiturage->getNbPlace() - 1);
    $credit->setAmount($credit->getAmount() - 1);

    $participation = new Participation();
    $participation->setUser($user);
    $participation->setCovoiturage($covoiturage);
    $participation->setDateParticipation(new \DateTime());

    $em->persist($participation);
    $em->flush();
    $dm->flush();

    $this->addFlash('success', 'Vous êtes inscrit à ce trajet ! 1 crédit utilisé.');
    return $this->redirectToRoute('user_profile');
    }




    #[Route('/edit/{id}', name: 'edit')]
    #[Route('/create', name: 'create')]
    #[IsGranted('ROLE_USER')]
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        VoitureRepository $voitureRepo,
        ?Covoiturage $covoiturage = null
    ): Response {
        $isCreate = false;
        $user = $this->getUser();

        if (!$covoiturage) {
            $isCreate = true;
            $covoiturage = new Covoiturage();
            $covoiturage->setCreatedBy($user);

            // Associer la première voiture du user par défaut
            $voiture = $voitureRepo->findOneBy(['user' => $user]);
            if ($voiture) {
                $covoiturage->setVoiture($voiture);
            }
        }

        $form = $this->createForm(CovoiturageType::class, $covoiturage, [
            'user' => $user
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $covoiturage->setStatut(CovoiturageStatut::DRAFT);
            $em->persist($covoiturage);
            $em->flush();

            $this->addFlash('success', $isCreate ? 'Le covoiturage a été créé.' : 'Le covoiturage a été modifié.');

            return $this->redirectToRoute('covoiturage_list');
        }

        return $this->render('covoiturage/editco.html.twig', [
            'form' => $form,
            'is_create' => $isCreate,
        ]);
    }


    #[Route('/delete/{id}', name:'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $em, Covoiturage $covoiturage): RedirectResponse
    {
        $em->remove($covoiturage);
        $em->flush();
        $this->addFlash('success', 'L\'covoiturage a été supprimé');
        return $this->redirectToRoute('covoiturage_list');
    }
    
    #[Route('/recherche', name: 'recherche')]
    public function recherche(Request $request, CovoiturageRepository $repo): Response
    {
        $villeDepart = $request->query->get('depart');
        $villeArrivee = $request->query->get('arrivee');
        $date = $request->query->get('date');

        $resultats = [];

    if ($villeDepart && $villeArrivee && $date) {
        $resultats = $repo->rechercherTrajets($villeDepart, $villeArrivee, new \DateTime($date));
    }

        return $this->render('covoiturage/recherche.html.twig', [
            'resultats' => $resultats,
    ]);
    }

    #[Route('/ajax/recherche', name: 'covoiturage_ajax_recherche', methods: ['GET'])]
    public function ajaxRecherche(Request $request, CovoiturageRepository $repo): JsonResponse
    {
        $depart = $request->query->get('depart');
        $arrivee = $request->query->get('arrivee');
        $date = $request->query->get('date');

    if (!$depart || !$arrivee || !$date) {
        return new JsonResponse(['error' => 'Données manquantes'], 400);
    }

    $resultats = $repo->rechercherTrajets($depart, $arrivee, new \DateTime($date));

    $data = [];
    foreach ($resultats as $trajet) {
        $data[] = [
            'id' => $trajet->getId(),
            'lieuDepart' => $trajet->getLieuDepart(),
            'lieuArrivee' => $trajet->getLieuArrivee(),
            'date' => $trajet->getDateDepart()->format('d/m/Y'),
            'heure' => $trajet->getHeureDepart()->format('H:i'),
            'conducteur' => $trajet->getCreatedBy()->getFirstname(),
        ];
    }


    return new JsonResponse($data);
    }


}

