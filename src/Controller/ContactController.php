<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $this->addFlash('success', '✅ Votre message a bien été envoyé !');
            return $this->redirectToRoute('contact');
        }

        return $this->render('default/contact.html.twig');
    }
}
