<?php

namespace App\Controller;

use App\Entity\Statistical;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * index
     * Cette function affiche tous les produit stocker dans le panier
     * @return Object
     */
    #[Route('/cart', name: 'cart')]
    public function index(CartService $cartService){

        $panierWithData = $cartService->getFullCart();
        $total = $cartService->getTotal();
        $nbItem = count($panierWithData);

        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
            'nbItem' => $nbItem,
        ]);
    }

    //Ajouter un produit au panier
    #[Route('/cart/{id}', name: 'add_cart')]
    public function add($id, CartService $cartService, ProductRepository $productRepository){

        $cartService->add($id);
        $idSubCategory = $productRepository->findOneBy(['id'=>$id])->getSubCategory()->getId();
        $this->addFlash('ajouter', 'Un produit à été ajouté au panier.');

        return $this->redirectToRoute('all_product', ['id'=> $idSubCategory]);
    }

    // Ajouter un produit un par un dans le panier
    #[Route('/addCart/{id}', name: 'add_inCart')]
    public function addCart($id, CartService $cartService){
        
        $cartService->addCart($id);

        return $this->redirectToRoute("cart");
    }

    // Supprime le panier (lors du clic "Abandon de commande" ou "Supprimer le panier") et incrémente de 1 le compteur de panier abandonner
    #[Route('/cancel_order', name: 'cancel_order')]
    public function cancelOrder(CartService $cartService) {
        // remove commande
        $cartService->removeAll();

        // incrémentation du compteur du nombre d'abandon de commande
        $em = $this->getDoctrine()->getManager();
        $stat = $em->getRepository(Statistical::class)->find(1);
        $count = $stat->getAbandonedCart();
        $stat->setAbandonedCart(++$count);
        $em->persist($stat);
        $em->flush();

        return $this->redirectToRoute("home");
    }
    
    // Supprimer un produit dans sa totalité (lors du clic sur la poubelle)
    #[Route('/cart/remove/{id}', name: 'remove_cart')]
    public function removeCart($id, CartService $cartService){
        
        $cartService->remove($id);

        return $this->redirectToRoute("cart");
    }

    // Supprime un produit (lors du clic sur le signe moin(-))
    #[Route('/cart/removeOne/{id}', name: 'removeOne_cart')]
    public function removeOneCart($id, CartService $cartService){
        
        $cartService->removeOne($id);

        return $this->redirectToRoute("cart");
    }

    
}
