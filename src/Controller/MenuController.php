<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\ProductRepository;
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

        $subCategory = $this->getDoctrine()
                        ->getRepository(SubCategory::class)
                        ->findAll();           

        return $this->render('menu/menu.html.twig', [
            'categorys' => $category,
            'subCategorys' => $subCategory
        ]);
    }
}
