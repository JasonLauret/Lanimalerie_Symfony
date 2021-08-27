<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;

class DeliveryController extends AbstractController
{
    #[Route('/delivery', name: 'delivery')]
    public function index(CartService $cartService,ProductRepository $productRepository, EntityManagerInterface $em)
    {   
        $nbItem = $cartService->getFullCart();
        $nbItem = count($nbItem);

        //creation new objet commande
        $order = new Order();
        //date du jour de la commande
        $order->setDate(new \Datetime());
        //qui a fait la commande (celui qui est connecter)
        $order->setUser($this->getUser());
        //recupere le panier dans la session
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier');
        //enregistre la commande
        $em->persist($order);
        
        dump($panier);
        //parcour le panier
        foreach($panier as $key => $value){
            //creation d'un nouveau objet orderProduct (nouvelle ligne )
            $orderProduct = new OrderProduct();
            //lie cette ligne a la commande
            $order->addOrderProduct($orderProduct);
            // recupere le produit a partir de l'id
            $produit = $productRepository->find($key);
            //lie le produit a la commande
            $orderProduct->setProduct($produit);
            //on lui donne la quantité
            $orderProduct->setQuantity($value);
            //enregistrement
            $em->persist($orderProduct);
            
            dump($produit);
        }
        //execute les requetes
        $em->flush();

        return $this->render('content/delivery.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
            'nbItem' => $nbItem
        ]);
    }


    #[Route('/delivery/test/{order}', name: 'app_admin_export_communication')]
    public function exportCommunicationAction(Order $order)
    {
        dump($order);
        foreach($order->getOrderProducts() as $elem){
            dump($elem->getProduct()->getName());
        }
        die();
    }


    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
}
