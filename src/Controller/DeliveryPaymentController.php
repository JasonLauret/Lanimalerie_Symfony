<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Form\OrderType;
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
    #[Route('/deliveryPayment', name: 'delivery_payment')]
    public function deliveryPayment(Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response {

        $order = new Order();
        $form = $this->createForm(OrderType::class, $order, ['user'=>$this->getUser()]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            
            //-----------------
            //Date du jour de la commande
            $order->setDate(new \Datetime());
            //Qui à fait la commande (celui qui est connecter)
            $order->setUser($this->getUser());
            //Récupere le panier dans la session
            $session = $this->requestStack->getSession();
            $panier = $session->get('panier');
            //Récupere l'adresse de livraison
            // $order->setDelivery($this->getUser());
            //Enregistre la commande
            $entityManager->persist($order);
            
            //parcour le panier
            foreach($panier as $key => $value){
                //Création d'un nouveau objet orderProduct (nouvelle ligne dans orderProduct)
                $orderProduct = new OrderProduct();
                //Lie cette ligne à la commande
                $order->addOrderProduct($orderProduct);
                //Récupère le produit a partir de l'id
                $produit = $productRepository->find($key);
                //Lie le produit à la commande
                $orderProduct->setProduct($produit);
                //On lui donne la quantité
                $orderProduct->setQuantity($value);
                //Enregistrement
                $entityManager->persist($orderProduct);
                
                dump($produit);
            }
            //Exécution des requètes
            $entityManager->flush();
            //-----------------

            return $this->redirectToRoute("order_validated");
        }
        
        return $this->render('order_tunnel/deliveryPayment.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/validated', name: 'order_validated')]
    public function orderValidated(): Response {

        $order = $this->getDoctrine()
                    ->getRepository(Order::class)
                    ->findAll();
        
        return $this->render('order_tunnel/orderValidated.html.twig', [
            'order' => $order,
        ]);
    }

    // #[Route('/order/{id}', name: 'display_order')]
    // public function exportCommunicationAction(CartService $cartService)
    // {
    //     $total = $cartService->getTotal();
    //     $order = $this->getDoctrine()
    //                 ->getRepository(Order::class)
    //                 ->findAll();

    //     return $this->render('order_tunnel/purchaseOrder.html.twig', [
    //         'orders' => $order,
    //         'total' => $total
    //     ]);
    // }

    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }


//------------------------------------

    // #[Route('/deliveryPayment', name: 'delivery_payment')]
    // public function addPaymentMethod(Request $request): Response {

    //     $order = new Order();
    //     $form = $this->createForm(OrderType::class, $order, ['user'=>$this->getUser()]);
    //     $form->handleRequest($request);
        
    //     if ($form->isSubmitted() && $form->isValid()){
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($order);
    //         $entityManager->flush();

    //         return $this->redirectToRoute("new_order");
    //     }
        
    //     return $this->render('order_tunnel/deliveryPayment.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }
}