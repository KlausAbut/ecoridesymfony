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

#[Route('/laisser-un-avis', name: 'avis_create')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
public function createAvis(Request $request, EntityManagerInterface $em): Response
    {
        $avis = new Avis();
        $avis->setUser($this->getUser());

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($avis);
            $em->flush();

            $this->addFlash('success', 'Merci pour votre avis !');
            return $this->redirectToRoute('index');
        }

        return $this->render('avis/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
