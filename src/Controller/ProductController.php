<?php

namespace App\Controller;


use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class ProductController extends AbstractController
{
    /**
     * product
     *
     * Cette fonction affiche tous les produits par rapport à sa catégorie parent
     * @param  mixed $productRepository // Injecte la class ProductRepository, pour accéder à ses fonctions.
     * @param  int $id // Prend l'id de l'URL, et l'ajoute en paramètre de la fonction getProductByCategory(). Correspond à l'id de la sous-categorie.
     * @return array // Retourne un tableau contenant tous les produits par rapport à sa sous-categorie.
     */
    #[Route('/category/{id}', name: 'all_product')]
    public function allProduct(ProductRepository $productRepository, $id, PaginatorInterface $paginator, Request $request)
    {
        $productByCategory = $productRepository->getProductByCategory($id);
        
        $product = $paginator->paginate($productByCategory, $request->query->getInt('page', 1), 8);

        return $this->render('product/allProduct.html.twig', [
            'products' => $product,
        ]);
    }

    /**
     * allProductAdmin
     * 
     * Cette fonction affiche tous les produits de BDD
     * @return array // Retourne un tableau contenant tous les produits de la table product.
     */
    #[Route('/admin/product', name: 'all_productAdmin')]
    public function allProductAdmin(PaginatorInterface $paginator, Request $request)
    {
        $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->findAll();

        $product = $paginator->paginate($product, $request->query->getInt('page', 1), 8);

        return $this->render('product/allProductAdmin.html.twig', [
            'products' => $product,
        ]);
    }

    /**
     * displayProductAdmin
     * 
     * Cette fonction affiche les informations d'un produit.
     * @param  int $id // Prend l'id de l'URL, et l'ajoute en paramètre de la fonction find().
     * @return array // Retourne un tableau contenant les informations du produits.
     */
    #[Route('/admin/product/{id}', name: 'display_productAdmin')]
    public function displayProductAdmin($id)
    {
        $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->find($id);

        return $this->render('product/displayProductAdmin.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * displayProduct
     * 
     * Cette fonction affiche les informations d'un produit. Elle affiche aussi 3 produits de la même sous-catégorie.
     * @param  int $id // Prend l'id de l'URL, et l'ajoute en paramètre de la fonction find().
     * @param  mixed $productRepository // Injecte la class ProductRepository, pour accéder à ses fonctions.
     * @return array // Retourne deux tableaux, un contenant les informations du produits. l'autre contenant trois produits de la même catégories.
     */
    #[Route('/display/product/{id}', name: 'display_product')]
    public function displayProduct($id, ProductRepository $productRepository)
    {
        $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->find($id);
                    
        $idCourant = $productRepository->similarProduct($product->getSubCategory()->getId());

        return $this->render('product/displayProduct.html.twig', [
            'product' => $product,
            'similarProducts' => $idCourant
        ]);
    }

    /**
     * addProduct
     * 
     * Cette créer une instance de la class Product, créer un formulaire via la class ProductType pour ajouter un nouveau produit.
     * @param  string $request // Récupère l'url et l'ajoute en paramètre de la fonction handleRequest() pour transmettre la requette HTTP.
     * @param  mixed $slugger
     * @return Response
     */
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
        }
        
        return $this->render('product/addProduct.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * updateProduct
     * Cette fonction permet la modification d'un produit via le formulaire ProductType
     * @param  string $request // Récupère l'url et l'ajoute en paramètre de la fonction handleRequest() pour transmettre la requette HTTP
     * @param  int $id // Prend l'id de l'URL, et l'ajoute en paramètre de la fonction find().
     * @param  mixed $slugger
     * @return Response
     */
    #[Route('/admin/updateProduct/{id}', name: 'update_product')]
    public function updateProduct(Request $request, $id, SluggerInterface $slugger): Response {

        $product = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($id);
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

    /**
     * deleteProduct
     * 
     * Cette function supprime un produit
     * @param  int $id // Prend l'id de l'URL, et l'ajoute en paramètre de la fonction find().
     * @return Vue
     */
    #[Route('/admin/deleteProduct/{id}', name: 'delete_product')]
    public function deleteProduct($id) {

        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager
                    ->getRepository(Product::class)
                    ->find($id);

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute("all_productAdmin");
    }
}