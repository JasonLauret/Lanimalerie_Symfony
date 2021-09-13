<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
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
        //__________Création de la commande__________

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


        return $this->render('order_tunnel/newOrder.html.twig', [
            'order' => $order,
        ]);
    }



    #[Route('/order/{id}', name: 'display_order')]
    public function exportCommunicationAction(Order $order, CartService $cartService)
    {
        $total = $cartService->getTotal();

        return $this->render('order_tunnel/purchaseOrder.html.twig', [
            'orders' => $order,
            'total' => $total
        ]);
    }


    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
}