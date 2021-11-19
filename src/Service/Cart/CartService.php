<?php

namespace App\Service\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository){
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    //_______add_______
    
    /**
     * add
     * // Ajoute un produit au panier
     * @return void
     */
    public function add($id){
        $panier = $this->session->get("panier", []);
        if(!empty($panier[$id])){
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $this->session->set('panier', $panier);
    }
    
    /**
     * addCart
     * // // Ajoute un produit (lors du clic sur le signe plus(+))
     * @return void
     */
    public function addCart($id){
        $panier = $this->session->get("panier", []);
        if(!empty($panier[$id])){
            $panier[$id]++;
        } else {
            $panier[$id] + 1;
        }
        $this->session->set('panier', $panier);
    }

    //_______remove_______
    
    /**
     * removeAll
     * // Supprime le panier (lors du clic "Abandon de commande")
     * @return void
     */
    public function removeAll(){
        $this->session->set('panier', []);
    }
    
    /**
     * remove
     * // Supprime un produit dans sa totalité (lors du clic sur la poubelle)
     * @return void
     */
    public function remove($id){
        $panier = $this->session->get('panier', []);
        if(!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);
    }
    
    /**
     * removeOne
     * // Supprime un produit (lors du clic sur le signe moin(-))
     * @return void
     */
    public function removeOne($id){
        $panier = $this->session->get('panier', []);
        if(!empty($panier[$id])) {
            $panier[$id] -= 1;
            if(!empty($panier[$id]) < 1) {
                unset($panier[$id]);
            }
        }
        $this->session->set('panier', $panier);
    }

    //______________
    
    /**
     * getFullCart
     * // Fonction qui affiche les produits
     * @return Object
     */
    public function getFullCart(){
        $panier = $this->session->get('panier', []);
        $panierWithData = [];
        foreach($panier as $id => $quantity) {
            $panierWithData[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
        }
        return $panierWithData;
    }

    //______________Total_______________
    
    /**
     * getTotal
     * // Fontion qui calcul le montant total du panier
     * @return
     */
    public function getTotal(){
        $total = 0;
        foreach($this->getFullCart() as $item){
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }
}