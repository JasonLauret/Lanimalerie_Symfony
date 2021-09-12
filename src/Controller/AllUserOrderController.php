<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllUserOrderController extends AbstractController
{
    #[Route('/all/user/order/{id}', name: 'all_user_order')]
    public function allOrderUser(OrderRepository $orderRepository/*, Order $order*/, $id): Response
    {
        $orderRepository = $orderRepository->allUserOrder($id);


        return $this->render('all_user_order/allUserOrder.html.twig', [
            'allOrder' => $orderRepository,
            // 'orders' => $order
        ]);
    }
}
