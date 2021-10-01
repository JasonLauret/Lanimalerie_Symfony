<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository){
        $this->productRepository = $productRepository;
    }

    #[Route('/search', name: 'search')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $results = $this->productRepository->findAll();

        if($form->isSubmitted() and $form->isValid()){
            $results = $this->productRepository->searchProduct($form->getData());
        }

        return $this->render('search/index.html.twig', [
            'form' => $form->createView(),
            'results' => $results,
        ]);
    }
}
