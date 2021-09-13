<?php

namespace App\Controller;

use App\Entity\PaymentMethod;
use App\Form\PaymentMethodType;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class paymentMethodController extends AbstractController
{   

    #[Route('/payment', name: 'payment')]
    public function addPaymentMethod(Request $request): Response {
        
        $paymentMethode = new PaymentMethod();
        $form = $this->createForm(PaymentMethodType::class, $paymentMethode);
        $form->handleRequest($request);

        dump($paymentMethode);
        
        if ($form->isSubmitted() && $form->isValid()){
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($paymentMethode);
            $entityManager->flush();

            return $this->redirectToRoute('payment');
        }
        
        return $this->render('order_tunnel/paymentMethod.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // #[Route('/payment/{order}', name: 'payment')]
    // public function addPaymentMethod(Request $request, Order $order): Response {
        
        
    //     $form = $this->createForm(PaymentMethodType::class, $order);
    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid()){
            
    //         $entityManager = $this->getDoctrine()->getManager();
            
    //         $entityManager->flush();


    //         return $this->redirectToRoute('payment');
    //     }
        
    //     return $this->render('order_tunnel/paymentMethod.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }
}