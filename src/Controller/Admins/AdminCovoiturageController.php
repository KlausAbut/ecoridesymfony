<?php

namespace App\Controller\Admins;

use App\Entity\Covoiturage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/covoiturage', 'admin_covoiturage_')]
class AdminCovoiturageController extends AbstractController
{
    #[Route('/validate/{id}', name:'validate')]
    public function list(EntityManagerInterface $em, Covoiturage $covoiturage = null): RedirectResponse
    {
        if (!$covoiturage) {
            throw $this->createNotFoundException('Covoiturage non trouvé.');
        }
        
        
        $covoiturage->setStatut('Publie');
        $em->persist($covoiturage);
        $em->flush();

        return $this->redirectToRoute('covoiturage_show',['id'=> $covoiturage->getId()]);
    }

}