<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }
        
        $total = 0;
        foreach($panierWithData as $item){
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total
        ]);
    }
    

    #[Route('/cart/{id}', name: 'add_cart')]

    public function add($id, SessionInterface $session){
        // Récuper le panier actuelle
        $panier = $session->get("panier", []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute("cart");
    }


    #[Route('/cart/remove/{id}', name: 'remove_cart')]
    public function removeCart($id, SessionInterface $session){
        $panier = $session->get('panier', []);
        if(!empty($panier[$id])) {
            $panier[$id] -= 1;
            if(!empty($panier[$id]) < 1) {
                unset($panier[$id]);
            }
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute("cart");
    }
}
