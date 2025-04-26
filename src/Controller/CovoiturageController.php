<?php

namespace App\Controller;

use App\Entity\Covoiturage;
use App\Form\CovoiturageType;
use App\Enum\CovoiturageStatut;
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


#[Route('covoiturage', name: 'covoiturage_')]
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

    #[Route('/participer/{id}', name: 'participer', methods: ['POST'])]
    public function participer(Covoiturage $covoiturage, EntityManagerInterface $em, DocumentManager $dm): RedirectResponse
    {
    $user = $this->getUser();

    if (!$user) {
        throw $this->createAccessDeniedException();
    }

    $credit = $dm->getRepository(UserCredit::class)->findOneBy(['user' => $user]);

    if (!$credit || $credit->getAmount() < 1) {
        $this->addFlash('danger', 'Vous n\'avez pas assez de crédits pour participer.');
        return $this->redirectToRoute('user_profile');
    }

    if ($covoiturage->getNbPlace() <= 0) {
        $this->addFlash('danger', 'Plus de place disponible pour ce trajet.');
        return $this->redirectToRoute('user_profile');
    }

    // Tout est bon ➔ participation
    $covoiturage->setNbPlace($covoiturage->getNbPlace() - 1);
    $credit->setAmount($credit->getAmount() - 1);

    $covoiturage->addParticipant($user);

    $em->flush();
    $dm->flush();

    $this->addFlash('success', 'Vous êtes inscrit à ce trajet ! 1 crédit utilisé.');
    return $this->redirectToRoute('user_profile');
}



    #[Route('/edit/{id}', name:'edit')]
    #[Route('/create', name:'create')]
    #[IsGranted('conducteur')]
    public function edit(Request $request, EntityManagerInterface $em, ?Covoiturage $covoiturage = null): Response
    {
        $isCreate = false;
        if(!$covoiturage){
            $isCreate = true;
            $covoiturage = new Covoiturage();

            $covoiturage->setCreatedBy($this->getUser());
            
        }

        $form = $this->createForm(CovoiturageType::class, $covoiturage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Covoiturage $covoiturage */
            $covoiturage = $form->getData();
            
            $covoiturage->setStatut(CovoiturageStatut::DRAFT);
            
            $em->persist($covoiturage);
            $em->flush();

            $this->addFlash('success', $isCreate ? 'L\'covoiturage a été créé' : 'L\'covoiturage a été modifié');

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

}

