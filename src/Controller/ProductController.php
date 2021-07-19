<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddProductType;
use App\Form\UpdateProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="all_product")
     */
    public function allProduct()
    {
        $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->findAll();

        return $this->render('product/allProduct.html.twig', [
            'products' => $product,
        ]);
    }

    /**
     * @Route("/admin/productAdmin", name="all_productAdmin")
     */
    public function allProductAdmin()
    {
        $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->findAll();

        return $this->render('product/allProductAdmin.html.twig', [
            'products' => $product,
        ]);
    }

    /**
     * @Route("/product/{id}", name="display_product")
     */
    public function displayProduct($id)
    {
        $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->find($id);

        if (!$product){
            //throw $this->createNotFoundException("Le produit demandée n'existe pas");
            return $this->render('product/error.html.twig', ['product' => $product,]);
        }

        return $this->render('product/displayProduct.html.twig', ['product' => $product,]);
    }

    //Filtrer
    /**
     * @Route("/product/name/{name}", name="display_product_name")
     */
    public function displayProductByName($name)
    {
        $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->findBy(['name'=>$name]);


        return $this->render('product/allProduct.html.twig', ['products' => $product,]);
    }
    

    /**
     * @Route("/admin/addProduct", name="add_productAdmin")
     */
    public function addProduct(Request $request): Response { //La function est bien pour les produit et non pas pour les categorie
        
        $form = $this->createForm(AddProductType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $product = new Product();
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->setPrice($data['price']);
            $product->setBrand($data['brand']);
            foreach($data['category'] as $valeur){
                $product->addCategory($valeur);
            }

           // var_dump($data['category']);
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute("all_product");
            //return new Response('Le produit a été ajoutée '.$product->getName());
        }
        
        return $this->render('product/addProduct.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/updateProduct/{id}", name="update_product")
     */
    public function updateProduct(Request $request, $id): Response {

        $product = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($id);

        if(!$product){
            return $this->render('product/error.html.twig',['error' => 'Le produit n\'existe pas'] );
        }

        $form = $this->createForm(AddProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            
            return $this->redirectToRoute("display_product", [
                "id" => $product->getId()]);
        }

        return $this->render('product/addProduct.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/deleteProduct/{id}", name="delete_product")
     */
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

        return $this->redirectToRoute("all_product");
    }
}