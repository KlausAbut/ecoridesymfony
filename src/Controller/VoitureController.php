<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\VoitureRepository;

#[Route('voiture', name: 'voiture_')]
class VoitureController extends AbstractController
{
    #[Route('/', name:'list')]
    public function list(VoitureRepository $voitureRepository): Response
    {
        return $this->render('voiture/list.html.twig', [
            'voitures' => $voitureRepository->findAll(),
        ]);
    }

    #[Route('/show/{id}', name:'show')]
    public function show(Voiture $voiture = null)
    {
        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture
        ]);
    }

    #[Route('/edit/{id}', name:'edit')]
    #[Route('/create', name:'create')]
    public function edit(Request $request, EntityManagerInterface $em, ?Voiture $voiture = null): Response
    {
        $isCreate = false;
        if(!$voiture){
            $isCreate = true;
            $voiture = new Voiture();
        }

        $form = $this->createForm(VoitureType::class, $voiture);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Voiture $voiture */
            $voiture = $form->getData();
            
            $voiture->setStatut('DRAFT');

            $voiture->setUser($this->getUser());
            
            $em->persist($voiture);
            $em->flush();

            $this->addFlash('success', $isCreate ? 'L\'voiture a été créé' : 'L\'voiture a été modifié');

            return $this->redirectToRoute('voiture_list');
        }

        return $this->render('voiture/edit.html.twig', [
            'form' => $form->createView(),
            'is_create' => $isCreate,
        ]);
    }

    #[Route('/delete/{id}', name:'delete')]
    public function delete(EntityManagerInterface $em, Voiture $voiture): RedirectResponse
    {
        $em->remove($voiture);
        $em->flush();
        $this->addFlash('success', 'L\'voiture a été supprimé');
        return $this->redirectToRoute('voiture_list');
    }
}