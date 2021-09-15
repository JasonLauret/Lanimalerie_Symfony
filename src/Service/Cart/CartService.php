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

    public function add($id){
        $panier = $this->session->get("panier", []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }

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

    public function remove($id){
        $panier = $this->session->get('panier', []);
        if(!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);
    }

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

    public function getTotal(){

        $total = 0;

        foreach($this->getFullCart() as $item){
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }
}