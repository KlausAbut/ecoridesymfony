<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $email = $request->request->get('email');
            $message = $request->request->get('message');

            $emailMessage = (new Email())
                ->from($email)
                ->to('contact@ecoride.fr') // destinataire fictif
                ->subject("Nouveau message de $nom")
                ->text("Email : $email\n\nMessage :\n$message");

            $mailer->send($emailMessage);

            $this->addFlash('success', 'Votre message a bien été envoyé.');
            return $this->redirectToRoute('contact');
        }

        return $this->render('default/contact.html.twig');
    }
}
