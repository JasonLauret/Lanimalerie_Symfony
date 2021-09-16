<?php

namespace App\Controller;

use App\Repository\AdressRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SummaryCartController extends AbstractController
{
    #[Route('/summaryCart', name: 'summary_cart')]
    public function index(CartService $cartService, AdressRepository $adressRepository)
    {   
        $nbItem = $cartService->getFullCart();
        $nbItem = count($nbItem);
        
        return $this->render('order_tunnel/summaryCart.html.twig', [
            'adresses' => $adressRepository->displayAdressById($this->getUser()),
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
            'nbItem' => $nbItem,
        ]);
    }
}