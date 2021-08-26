<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NavBarController extends AbstractController
{
    #[Route('/nav/bar', name: 'nav_bar')]
    public function index(): Response
    {
        $category = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->findAll();

        return $this->render('nav_bar/navBar.html.twig', [
            'controller_name' => 'NavBarController',
            'categorys' => $category
        ]);
    }
}
