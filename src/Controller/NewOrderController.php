<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewOrderController extends AbstractController
{
    #[Route('/new/order', name: 'new_order')]
    public function index(ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        //_____Création de la commande_____

        //Création new objet commande
        $order = new Order();
        //Date du jour de la commande
        $order->setDate(new \Datetime());
        //Qui à fait la commande (celui qui est connecter)
        $order->setUser($this->getUser());
        //Récupere le panier dans la session
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier');
        //Enregistre la commande
        $em->persist($order);
        
        dump($panier);
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
            $em->persist($orderProduct);
            
            dump($produit);
        }
        //Exécution des requètes
        $em->flush();


        return $this->render('new_order/newOrder.html.twig', [
            'order' => $order,
        ]);
    }


    #[Route('/order/purchase/{id}', name: 'display_order')]
    public function display_order($id)
    {
        $order = $this->getDoctrine()
                    ->getRepository(Order::class)
                    ->findAll($id);

        $order_product = $this->getDoctrine()
                        ->getRepository(OrderProduct::class)
                        ->findAll($id);

        return $this->render('content/purchaseOrder.html.twig', [
            'orders' => $order,
            'order_products' => $order_product,
        ]);
    }

    // #[Route('/delivery/test/{id}', name: 'display_order')]
    // public function exportCommunicationAction(Order $order)
    // {
    //     dump($order);
    //     foreach($order->getOrderProducts() as $elem){
    //         echo $elem->getProduct()->getName();
    //     }
    //     die();
        
    
    //     $order = $this->getDoctrine()
    //                 ->getRepository(Order::class)
    //                 ->findAll();


    //     return $this->render('content/purchaseOrder.html.twig', [
    //         'order' => $order,
    //     ]);
    // }


    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
}
