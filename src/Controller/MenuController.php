<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'menu')]
    public function index(ProductRepository $productRepository/*, SubCategoryRepository $subCategory, $id*/): Response
    {   
        $category = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->findAll();

        //$subCategory = $subCategory->getAllSubCategory($id);
            
        //$product = $productRepository->getAllProduct($id);            

        return $this->render('menu/menu.html.twig', [
            'categorys' => $category,
            //'products' => $product,
            //'subCategorys' => $subCategory
        ]);
    }

    // fonction pour afficher les produits par rapport à la sous-categorie parent
    /**
     * @Route("/product/{id}", name="all_product")
     */
    /*public function product(ProductRepository $productRepository, $id)
    {
        $product = $productRepository->findAllProduct($id);

        return $this->render('product/allProduct.html.twig', [
            'products' => $product,
        ]);
    }*/
}
