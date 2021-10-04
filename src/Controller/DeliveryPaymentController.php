<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Form\OrdersType;
use App\Form\OrderType;
use App\Form\PaymentMethodType;
use App\Repository\AdressRepository;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;

class DeliveryPaymentController extends AbstractController
{   
    // #[Route('/delivery_payment', name: 'delivery_payment')]
    // public function index(ProductRepository $productRepository, EntityManagerInterface $em, Request $request): Response
    // {
    //     //__________Création de la commande__________

    //     //Création new objet commande
    //     $order = new Order();
    //     //Date du jour de la commande
    //     $order->setDate(new \Datetime());
    //     //Qui à fait la commande (celui qui est connecter)
    //     $order->setUser($this->getUser());
    //     //Récupere le panier dans la session
    //     $session = $this->requestStack->getSession();
    //     $panier = $session->get('panier');
    //     //Récupere l'adresse de livraison
    //     // $order->setDelivery($this->getUser());
    //     //Enregistre la commande
    //     $em->persist($order);
        
    //     //parcour le panier
    //     foreach($panier as $key => $value){
    //         //Création d'un nouveau objet orderProduct (nouvelle ligne dans orderProduct)
    //         $orderProduct = new OrderProduct();
    //         //Lie cette ligne à la commande
    //         $order->addOrderProduct($orderProduct);
    //         //Récupère le produit a partir de l'id
    //         $produit = $productRepository->find($key);
    //         //Lie le produit à la commande
    //         $orderProduct->setProduct($produit);
    //         //On lui donne la quantité
    //         $orderProduct->setQuantity($value);
    //         //Enregistrement
    //         $em->persist($orderProduct);
    //     }

    //     $form = $this->createForm(OrdersType::class, $order);
    //     $form->handleRequest($request);


    //     if ($form->isSubmitted() && $form->isValid()){

    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($order);
    //         //Exécution des requètes
    //         $em->flush();

    //         return $this->redirectToRoute("new_order");
    //     }

    //     return $this->render('order_tunnel/deliveryPayment.html.twig', [
    //         'order' => $order,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // private $requestStack;

    // public function __construct(RequestStack $requestStack)
    // {
    //     $this->requestStack = $requestStack;
    // }



    // #[Route('/deliveryPayment', name: 'delivery_payment')]
    // public function addPaymentMethod(AdressRepository $adressRepository): Response {

        
    //     return $this->render('order_tunnel/deliveryPayment.html.twig', [
    //         'adresses' => $adressRepository->displayAdressById($this->getUser())
    //     ]);
    // }


    #[Route('/deliveryPayment', name: 'delivery_payment')]
    public function addPaymentMethod(Request $request): Response {

        $order = new Order();
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute("new_order");
        }
        
        return $this->render('order_tunnel/deliveryPayment.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // #[Route('/deliveryPayment', name: 'delivery_payment')]
    // public function addPaymentMethod(Request $request): Response {
        // $adress = $request->request->get("adress");
        
        // $paymentMethode = new PaymentMethod();
        // $form = $this->createForm(PaymentMethodType::class, $paymentMethode);
        // $form->handleRequest($request);
        
        // if ($form->isSubmitted() && $form->isValid()){
            
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($paymentMethode);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('payment');
        // }
        
        // return $this->render('order_tunnel/deliveryPayment.html.twig', [
        //     'form' => $form->createView()
        // ]);
    //}

    

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