<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class purchaseOrderController extends AbstractController
{
    #[Route('/order/{id}', name: 'display_order')]
    public function exportCommunicationAction(Order $order, CartService $cartService)
    {
        $total = $cartService->getTotal();

        return $this->render('all_user_order/purchaseOrder.html.twig', [
            'orders' => $order,
            'total' => $total
        ]);
    }
}