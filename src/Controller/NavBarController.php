<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NavBarController extends AbstractController
{
    public function index(): Response
    {
        $category = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->findAll();

        return $this->render('content/include/nav.html.twig', [
            'categorys' => $category
        ]);
    }
}
