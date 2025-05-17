<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EMPLOYE')]
#[Route('/employe', name: 'employe_')]
class EmployeController extends AbstractController
{
    #[Route('/moderation', name: 'moderation')]
    public function moderation(AvisRepository $repo): Response
    {
        $avisSignalés = $repo->findBy(['valide' => false]);

        return $this->render('employe/moderation.html.twig', [
            'avis' => $avisSignalés
        ]);
    }

    #[Route('/moderation/valider/{id}', name: 'avis_valider')]
    public function validerAvis(int $id, AvisRepository $repo, EntityManagerInterface $em): Response
    {
        $avis = $repo->find($id);
        if ($avis) {
            $avis->setValide(true);
            $avis->setStatut('VALIDÉ');
            $em->flush();
            $this->addFlash('success', 'Avis validé avec succès.');
        }
        return $this->redirectToRoute('employe_moderation');
    }

    #[Route('/moderation/supprimer/{id}', name: 'avis_supprimer')]
    public function supprimerAvis(int $id, AvisRepository $repo, EntityManagerInterface $em): Response
    {
        $avis = $repo->find($id);
        if ($avis) {
            $em->remove($avis);
            $em->flush();
            $this->addFlash('success', 'Avis supprimé.');
        }
        return $this->redirectToRoute('employe_moderation');
    }
}