<?php

namespace App\Controller;

use App\Entity\Covoiturage;
use App\Form\CovoiturageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CovoiturageRepository;

#[Route('covoiturage', name: 'covoiturage_')]
class CovoiturageController extends AbstractController
{
    #[Route('/', name:'list')]
    public function list(CovoiturageRepository $covoiturageRepository): Response
    {
        return $this->render('covoiturage/list.html.twig', [
            'covoiturages' => $covoiturageRepository->findAll(),
        ]);
    }

    #[Route('/show/{id}', name:'show')]
    public function show(Covoiturage $covoiturage = null)
    {
        return $this->render('covoiturage/show.html.twig', [
            'covoiturage' => $covoiturage
        ]);

    }

    #[Route('/edit/{id}', name:'edit')]
    #[Route('/create', name:'create')]
    public function edit(Request $request, EntityManagerInterface $em, ?Covoiturage $covoiturage = null): Response
    {
        $isCreate = false;
        if(!$covoiturage){
            $isCreate = true;
            $covoiturage = new Covoiturage();
        }
        $covoiturage = new Covoiturage();

        $form = $this->createForm(CovoiturageType::class, $covoiturage);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Covoiturage $covoiturage */
            $covoiturage = $form->getData();
            
            $covoiturage->setStatut('DRAFT');
            
            $em->persist($covoiturage);
            $em->flush();

            $this->addFlash('success', $isCreate ? 'L\'covoiturage a été créé' : 'L\'covoiturage a été modifié');

            return $this->redirectToRoute('covoiturage_list');
        }

        return $this->render('covoiturage/edit.html.twig', [
            'form' => $form,
            'is_Create' => $isCreate
        ]);
    }

    #[Route('/delete/{id}', name:'delete')]
    public function delete(EntityManagerInterface $em, Covoiturage $covoiturage): RedirectResponse
    {
        $em->remove($covoiturage);
        $em->flush();
        $this->addFlash('success', 'L\'covoiturage a été supprimé');
        return $this->redirectToRoute('covoiturage_list');
    }
}