<?php

namespace App\Controller\Admin;

use App\Repository\AvisRepository;
use App\Repository\CovoiturageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\VoitureRepository;
use App\Entity\Voiture;


#[IsGranted('ROLE_ADMIN')]
#[Route('/admin', name: 'admin_')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(AvisRepository $avisRepo, CovoiturageRepository $covoiturageRepo, VoitureRepository $voitureRepo): Response
    {
        $avis = $avisRepo->findBy(['statut' => 'EN_ATTENTE'], ['id' => 'DESC']);
        $covoiturages = $covoiturageRepo->findBy(['statut' => 'DRAFT'], ['id' => 'DESC']);
        $voitures = $voitureRepo->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'avis' => $avis,
            'covoiturages' => $covoiturages,
            'voitures' => $voitures,
        ]);
    }

    #[Route('/avis/valider/{id}', name: 'avis_valider')]
    public function validerAvis(int $id, AvisRepository $repo, EntityManagerInterface $em): Response
    {
        $avis = $repo->find($id);
        if ($avis) {
            $avis->setStatut('VALIDÉ');
            $em->flush();
            $this->addFlash('success', 'Avis validé.');
        }

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/avis/supprimer/{id}', name: 'avis_supprimer')]
    public function supprimerAvis(int $id, AvisRepository $repo, EntityManagerInterface $em): Response
    {
        $avis = $repo->find($id);
        if ($avis) {
            $em->remove($avis);
            $em->flush();
            $this->addFlash('success', 'Avis supprimé.');
        }

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/covoiturage/valider/{id}', name: 'covoiturage_valider')]
    public function validerCovoiturage(int $id, CovoiturageRepository $repo, EntityManagerInterface $em): Response
    {
        $covoiturage = $repo->find($id);
        if ($covoiturage) {
            $covoiturage->setStatut('PUBLISHED');
            $em->flush();
            $this->addFlash('success', 'Covoiturage validé.');
        }

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/covoiturage/supprimer/{id}', name: 'covoiturage_supprimer')]
    public function supprimerCovoiturage(int $id, CovoiturageRepository $repo, EntityManagerInterface $em): Response
    {
        $covoiturage = $repo->find($id);
        if ($covoiturage) {
            $em->remove($covoiturage);
            $em->flush();
            $this->addFlash('success', 'Covoiturage supprimé.');
        }

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/voiture/supprimer/{id}', name: 'voiture_supprimer')]
    public function supprimerVoiture(Voiture $voiture, EntityManagerInterface $em): Response
    {
        $em->remove($voiture);
        $em->flush();

        $this->addFlash('success', 'Voiture supprimée.');

        return $this->redirectToRoute('admin_dashboard');
    }

}