<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryController extends AbstractController
{
    #[Route('/delivery', name: 'delivery')]
    public function index(CartService $cartService)
    {
        $nbItem = $cartService->getFullCart();
        $nbItem = count($nbItem);

        return $this->render('content/delivery.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal(),
            'nbItem' => $nbItem
        ]);
    }
}
