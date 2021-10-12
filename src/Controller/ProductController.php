<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class ProductController extends AbstractController
{

    #[Route('/category/{id}', name: 'all_product')]
    public function product(ProductRepository $productRepository, $id)
    {
        $product = $productRepository->getProductByCategory($id);

        return $this->render('product/allProduct.html.twig', [
            'products' => $product,
        ]);
    }

    #[Route('/admin/product', name: 'all_productAdmin')]
    public function allProductAdmin()
    {
        $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->findAll();

        return $this->render('product/allProductAdmin.html.twig', [
            'products' => $product,
        ]);
    }


    #[Route('/admin/product/{id}', name: 'display_productAdmin')]
    public function displayProductAdmin($id)
    {
        $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->find($id);

        if (!$product){
            //throw $this->createNotFoundException("Le produit demandée n'existe pas");
            return $this->render('product/error.html.twig', ['product' => $product,]);
        }

        return $this->render('product/displayProductAdmin.html.twig', [
            'product' => $product,
        ]);
    }


    #[Route('/display/product/{id}', name: 'display_product')]
    public function displayProduct($id, ProductRepository $productRepository)
    {
        $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->find($id);
                    
        $idCourant = $productRepository->similarProduct($product->getSubCategory()->getId());
        

        if (!$product){
            //throw $this->createNotFoundException("Le produit demandée n'existe pas");
            return $this->render('product/error.html.twig', ['product' => $product,]);
        }

        return $this->render('product/displayProduct.html.twig', [
            'product' => $product,
            'similarProducts' => $idCourant
        ]);
    }


    //Bon
    // #[Route('/display/product/{id}', name: 'display_product')]
    // public function displayProduct($id)
    // {
    //     $product = $this->getDoctrine()
    //                 ->getRepository(Product::class)
    //                 ->find($id);

    //     if (!$product){
    //         //throw $this->createNotFoundException("Le produit demandée n'existe pas");
    //         return $this->render('product/error.html.twig', ['product' => $product,]);
    //     }

    //     return $this->render('product/displayProduct.html.twig', [
    //         'product' => $product,
    //     ]);
    // }

    //Filtrer
    #[Route('/product/name/{name}', name: 'display_product_name')]
    public function displayProductByName($name)
    {
        $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->findBy(['name'=>$name]);


        return $this->render('product/allProduct.html.twig', ['products' => $product,]);
    }
    
    #[Route('/admin/addProduct', name: 'add_productAdmin')]
    public function addProduct(Request $request, SluggerInterface $slugger): Response { //La function est bien pour les produit et non pas pour les categorie
        
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()){

            $pictureFile = $form->get('picture')->getData();
            if($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();
                try{
                    $pictureFile->move($this->getParameter('upload_directory'), $newFilename);
                }
                catch (FileException $e) {
                    var_dump($e);
                    die('Erreur' );
                }
                $product->setPicture($newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute("all_productAdmin");
            //return new Response('Le produit a été ajoutée '.$product->getName());
        }
        
        return $this->render('product/addProduct.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/updateProduct/{id}', name: 'update_product')]
    public function updateProduct(Request $request, $id, SluggerInterface $slugger): Response {

        $product = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($id);

        if(!$product){
            return $this->render('product/error.html.twig',['error' => 'Le produit n\'existe pas'] );
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $pictureFile = $form->get('picture')->getData();

            if($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try{
                    $pictureFile->move($this->getParameter('upload_directory'), $newFilename);
                }
                catch (FileException $e) {
                    var_dump($e);
                    die('Erreur');
                }

                $product->setPicture($newFilename);

            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            
            return $this->redirectToRoute("display_productAdmin", [
                "id" => $product->getId()]);
        }

        return $this->render('product/editProduct.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/admin/deleteProduct/{id}', name: 'delete_product')]
    public function deleteProduct($id): Response {

        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager
                    ->getRepository(Product::class)
                    ->find($id);
        
        if(!$product){
            return $this->render('product/error.html.twig',['error' => 'Le produit n\'existe pas'] );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute("all_productAdmin");
    }
}