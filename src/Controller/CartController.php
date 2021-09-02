<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(CartService $cartService)
    {
        $panierWithData = $cartService->getFullCart();
        $total = $cartService->getTotal();

        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total
        ]);
    }
    

    #[Route('/cart/{id}', name: 'add_cart')]

    public function add($id, CartService $cartService){

        $cartService->add($id);
        return $this->redirectToRoute('all_product',['id'=> $id]);

    }

    #[Route('/addCart/{id}', name: 'add_inCart')]

    public function addCart($id, CartService $cartService){

        $cartService->addCart($id);
        return $this->redirectToRoute("cart");

    }


    #[Route('/cart/remove/{id}', name: 'remove_cart')]
    public function removeCart($id, CartService $cartService){
        
        $cartService->remove($id);

        return $this->redirectToRoute("cart");
    }

    #[Route('/cart/removeOne/{id}', name: 'removeOne_cart')]
    public function removeOneCart($id, CartService $cartService){
        
        $cartService->removeOne($id);

        return $this->redirectToRoute("cart");
    }
}
