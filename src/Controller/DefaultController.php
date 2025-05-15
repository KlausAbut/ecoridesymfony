<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Enum\CovoiturageStatut;
use App\Document\UserCredit;
use App\Repository\AvisRepository;
use App\Repository\CovoiturageRepository;
use App\Repository\ParticipationRepository;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
public function index(Request $request, CovoiturageRepository $repo, AvisRepository $avisRepo, DocumentManager $dm): Response
{
    $resultats = [];

    if ($request->query->get('depart') && $request->query->get('arrivee') && $request->query->get('date')) {
        $resultats = $repo->rechercherTrajets(
            $request->query->get('depart'),
            $request->query->get('arrivee'),
            new \DateTime($request->query->get('date'))
        );
    }

    $avisList = $avisRepo->findBy(['statut' => 'VALIDÉ'], ['id' => 'DESC'], 10);

    $credit = null;
    $user = $this->getUser();

    if ($user) {
        $credit = $dm->getRepository(UserCredit::class)->findOneBy(['userId' => $user->getId()]);

        if (!$credit) {
            $credit = new UserCredit();
            $credit->setUserId($user->getId()); 
            $credit->setAmount(20);
            $dm->persist($credit);
            $dm->flush();
        }
    }

    return $this->render('default/accueil.html.twig', [
        'resultats' => $resultats,
        'avisList' => $avisList,
        'credit' => $credit,
    ]);
}



    #[Route('/mes-reservations', name: 'user_reservations')]
    public function mesReservations(): Response
    {
        $user = $this->getUser();
        $participations = $user->getParticipations();

        return $this->render('user/reservations.html.twig', [
            'participations' => $participations,
        ]);
    }

    #[Route('/annuler-reservation/{id}', name: 'annuler_reservation', methods: ['POST'])]
    public function annulerReservation(Participation $participation, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($participation->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $covoiturage = $participation->getCovoiturage();
        $covoiturage->setNbPlace($covoiturage->getNbPlace() + 1);

        $em->remove($participation);
        $em->flush();

        $this->addFlash('success', 'Votre réservation a été annulée.');

        return $this->redirectToRoute('user_reservations');
    }

    #[Route('/mes-trajets', name: 'user_trajets')]
    public function mesTrajets(): Response
    {
        $user = $this->getUser();
        $covoiturages = $user->getCovoituragesCrees();

        return $this->render('user/trajets.html.twig', [
            'covoiturages' => $covoiturages,
        ]);
    }

    #[Route('/profil', name: 'user_profile')]
    public function profil(
    VoitureRepository $voitureRepo,
    CovoiturageRepository $covoiturageRepo,
    ParticipationRepository $participationRepo,
    AvisRepository $avisRepo,
    DocumentManager $dm
    ): Response {
    $user = $this->getUser();
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $user = $this->getUser();

    $nombreTrajets = $covoiturageRepo->count(['createdBy' => $user]);
    $nombreReservations = $participationRepo->count(['user' => $user]);

    $avis = $avisRepo->findBy(['user' => $user, 'statut' => 'VALIDÉ']);
    $noteMoyenne = null;
    if (count($avis) > 0) {
        $noteMoyenne = array_sum(array_map(fn($a) => $a->getNote(), $avis)) / count($avis);
    }

    // Crédit
    $credit = $dm->getRepository(UserCredit::class)->findOneBy(['userId' => $user->getId()]);
    if (!$credit) {
        $credit = new UserCredit();
        $credit->setUserId($user->getId());
        $credit->setAmount(20);
        $dm->persist($credit);
        $dm->flush();
    }

    
    $allCovoiturages = $covoiturageRepo->createQueryBuilder('c')
        ->where('c.statut = :statut')
        ->andWhere('c.createdBy != :user')
        ->andWhere(':user NOT MEMBER OF c.participants')
        ->setParameter('statut', CovoiturageStatut::PUBLISHED)
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();

    return $this->render('user/profil.html.twig', [
        'user' => $user,
        'credit' => $credit,
        'voitures' => $voitureRepo->findBy(['user' => $user]),
        'covoiturages' => $covoiturageRepo->findBy(['createdBy' => $user]),
        'all_covoiturages' => $allCovoiturages,
        'nombreTrajets' => $nombreTrajets,
        'nombreReservations' => $nombreReservations,
        'nbAvis' => count($avis),
        'noteMoyenne' => $noteMoyenne,
    ]);
    }

    #[Route('/devenir-conducteur', name: 'user_become_conducteur')]
    public function devenirConducteur(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $user->setIsConducteur(true);
        $em->flush();

        $this->addFlash('success', 'Vous êtes maintenant conducteur. Vous pouvez créer votre voiture et proposer des trajets.');

        return $this->redirectToRoute('user_profile');
    }

    #[Route('/cgu', name: 'app_cgu')]
    public function cgu(): Response
    {
        return $this->render('default/cgu.html.twig');
    }


    #[Route('/a-propos', name: 'about')]
    public function about(): Response
    {
        return $this->render('default/about.html.twig');
    }

}
