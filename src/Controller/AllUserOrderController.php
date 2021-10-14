<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllUserOrderController extends AbstractController
{
    #[Route('/all/user/order/{id}', name: 'all_user_order')]
    public function allOrderUser(OrderRepository $orderRepository, $id, PaginatorInterface $paginator, Request $request): Response
    {
        $orderRepository = $orderRepository->allUserOrder($id);

        $allOrder = $paginator->paginate($orderRepository, $request->query->getInt('page', 1), 10);

        return $this->render('all_user_order/allUserOrder.html.twig', [
            'allOrders' => $allOrder,
        ]);
    }
}
