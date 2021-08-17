<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class paymentMethodController extends AbstractController
{
    #[Route('/payment', name: 'payment')]
    public function index(CartService $cartService)
    {
        return $this->render('content/paymentMethod.html.twig', [
            'items' => $cartService->getFullCart(),
            'total' => $cartService->getTotal()
        ]);
    }
}
