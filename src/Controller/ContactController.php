<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]

    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $message = (new Email())
                ->from($data['email'])
                ->to("lauret.jason73390@gmail.com")
                ->subject('Demande en provenance du site')
                ->text('From '. $data['email'].' '.$data['message'], 'text/plain');

            $mailer->send($message);

            return $this->redirectToRoute('contact/contact.html.twig');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
