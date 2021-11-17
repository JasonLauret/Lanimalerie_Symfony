<?php

namespace App\Controller;

use App\Repository\ProductRepository;
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
        $nbItem = count($panierWithData);

        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
            'nbItem' => $nbItem,
        ]);
    }

    //Ajouter un article au panier
    #[Route('/cart/{id}', name: 'add_cart')]
    public function add($id, CartService $cartService, ProductRepository $productRepository){
        $cartService->add($id);
        $idSubCategory = $productRepository->findOneBy(['id'=>$id])->getSubCategory()->getId();
        $this->addFlash('ajouter', 'Un produit à été ajouté au panier.');

        return $this->redirectToRoute('all_product', ['id'=> $idSubCategory]);
    }

    // Ajouter un article un par un dans le panier
    #[Route('/addCart/{id}', name: 'add_inCart')]
    public function addCart($id, CartService $cartService){
        $cartService->addCart($id);

        return $this->redirectToRoute("cart");
    }

    //Supprimer le panier
    #[Route('/cancel_order', name: 'cancel_order')]
    public function cancelOrder(CartService $cartService) {

        $cartService->removeAll();
        
        return $this->redirectToRoute("home");
    }
    
    //Supprimer completement un article
    #[Route('/cart/remove/{id}', name: 'remove_cart')]
    public function removeCart($id, CartService $cartService){
        
        $cartService->remove($id);

        return $this->redirectToRoute("cart");
    }

    //Supprimer un article un par un dans le panier
    #[Route('/cart/removeOne/{id}', name: 'removeOne_cart')]
    public function removeOneCart($id, CartService $cartService){
        
        $cartService->removeOne($id);

        return $this->redirectToRoute("cart");
    }

    
}
