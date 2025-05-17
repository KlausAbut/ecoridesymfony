<?php

namespace App\Controller\Admin;

use App\Document\UserCredit;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Repository\AvisRepository;
use App\Repository\CovoiturageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\VoitureRepository;
use App\Entity\Voiture;
use App\Enum\CovoiturageStatut;


#[IsGranted('ROLE_ADMIN')]
#[Route('/admin', name: 'admin_')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(
    AvisRepository $avisRepo,
    CovoiturageRepository $covoiturageRepo,
    VoitureRepository $voitureRepo,
    DocumentManager $dm
    ): Response {
    $avis = $avisRepo->findBy(['statut' => 'EN_ATTENTE'], ['id' => 'DESC']);
    $covoiturages = $covoiturageRepo->findBy(['statut' => CovoiturageStatut::DRAFT]);
    $voitures = $voitureRepo->findAll();

    $historiques = $covoiturageRepo->createQueryBuilder('c')
    ->where('c.date_depart < :now')
    ->andWhere('c.statut = :statut')
    ->setParameter('now', new \DateTime())
    ->setParameter('statut', CovoiturageStatut::PUBLISHED)
    ->orderBy('c.date_depart', 'DESC')
    ->getQuery()
    ->getResult();

    // Statistiques MongoDB
    $creditRepo = $dm->getRepository(UserCredit::class);
    $allCredits = $creditRepo->findAll();
    $totalCredits = array_sum(array_map(fn($c) => $c->getAmount(), $allCredits));

    $creditsByDay = [];
    foreach ($allCredits as $credit) {
        $day = (new \DateTime())->format('Y-m-d');
        $creditsByDay[$day] = ($creditsByDay[$day] ?? 0) + $credit->getAmount();
    }

    $chartLabels = array_keys($creditsByDay);
    $chartData = array_values($creditsByDay);
    $creditsToday = $creditsByDay[(new \DateTime())->format('Y-m-d')] ?? 0;

    return $this->render('admin/dashboard.html.twig', [
        'avis' => $avis,
        'covoiturages' => $covoiturages,
        'voitures' => $voitures,
        'historiques' => $historiques,
        'totalCredits' => $totalCredits,
        'creditsToday' => $creditsToday,
        'chartLabels' => $chartLabels,
        'chartData' => $chartData,
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
            $covoiturage->setStatut(CovoiturageStatut::PUBLISHED);
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
            foreach ($covoiturage->getParticipations() as $participation) {
                $em->remove($participation);
            }
            $em->remove($covoiturage);
            $em->flush();
            $this->addFlash('success', 'Covoiturage et participations supprimés.');
    }

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/voiture/supprimer/{id}', name: 'voiture_supprimer')]
    public function supprimerVoiture(Voiture $voiture, EntityManagerInterface $em): Response
    {
        if (count($voiture->getCovoiturages()) > 0) {
            $this->addFlash('danger', 'Impossible de supprimer cette voiture : elle est utilisée dans un covoiturage.');
            return $this->redirectToRoute('admin_dashboard');
        }

        $em->remove($voiture);
        $em->flush();
        $this->addFlash('success', 'Voiture supprimée.');
        return $this->redirectToRoute('admin_dashboard');
    }

}