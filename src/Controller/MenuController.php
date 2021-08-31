<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'menu')]
    public function index(): Response
    {   
        $category = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->findAll();

        return $this->render('menu/menu.html.twig', [
            'categorys' => $category
        ]);
    }
}
