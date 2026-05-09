<?php

namespace App\Controller;

use App\Enum\AvisStatut;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/moderation/valider/{id}', name: 'avis_valider', methods: ['POST'])]
    public function validerAvis(int $id, Request $request, AvisRepository $repo, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('avis_valider' . $id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide');
        }

        $avis = $repo->find($id);
        if ($avis) {
            $avis->setValide(true);
            $avis->setStatut(AvisStatut::VALIDE);
            $em->flush();
            $this->addFlash('success', 'Avis validé avec succès.');
        }
        return $this->redirectToRoute('employe_moderation');
    }

    #[Route('/moderation/supprimer/{id}', name: 'avis_supprimer', methods: ['POST'])]
    public function supprimerAvis(int $id, Request $request, AvisRepository $repo, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('avis_supprimer' . $id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide');
        }

        $avis = $repo->find($id);
        if ($avis) {
            $em->remove($avis);
            $em->flush();
            $this->addFlash('success', 'Avis supprimé.');
        }
        return $this->redirectToRoute('employe_moderation');
    }
}
