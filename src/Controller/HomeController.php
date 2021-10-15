<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\OrderProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(Request $request, MailerInterface $mailer, OrderProductRepository $orderProductRepository): Response {

        $bestSale = $orderProductRepository->getBestSales();

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
        }

        return $this->render('home/home.html.twig', [
            'form' => $form->createView(),
            'bestSales' => $bestSale
        ]);
    }
}
