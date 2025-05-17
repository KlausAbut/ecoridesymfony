<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class AvisController extends AbstractController{

    #[Route('/laisser-un-avis/{id}', name: 'avis_create')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createAvis(Covoiturage $covoiturage, Request $request, EntityManagerInterface $em): Response
    {
        $existing = $em->getRepository(Avis::class)->findOneBy([
            'user' => $this->getUser(),
            'covoiturage' => $covoiturage
        ]);
    
        if ($existing) {
            $this->addFlash('warning', 'Vous avez déjà laissé un avis pour ce trajet.');
            return $this->redirectToRoute('user_reservations');
        }
    
        $avis = new Avis();
        $avis->setUser($this->getUser());
        $avis->setCovoiturage($covoiturage);
        $avis->setStatut('EN_ATTENTE');
    
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($avis);
            $em->flush();
    
            $this->addFlash('success', 'Merci pour votre avis ! Il sera publié après modération.');
            return $this->redirectToRoute('user_reservations');
        }
    
        return $this->render('avis/create.html.twig', [
            'form' => $form->createView(),
            'covoiturage' => $covoiturage
        ]);
    }

    #[Route('/avis', name: 'avis_list')]
    public function listAvis(EntityManagerInterface $em): Response
    {
        $avis = $em->getRepository(Avis::class)->findBy(
            ['statut' => 'VALIDÉ'],
            ['id' => 'DESC']
        );

        return $this->render('avis/list.html.twig', [
            'avis' => $avis
        ]);
    }

}
