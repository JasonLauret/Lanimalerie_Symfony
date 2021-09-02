<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\AddCateroryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categoryAdmin", name="all_category")
     */
    public function allCategoryAdmin()
    {
        $category = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->findAll();

        return $this->render('category/allCategory.html.twig', [
            'categorys' => $category,
        ]);
    }

    /**
     * @Route("/admin/category/{id}", name="display_category")
     */
    public function displayCategory($id)
    {
        $category = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->find($id);

        if (!$category){
            //throw $this->createNotFoundException("La catégorie demandée n'existe pas");
            return $this->render('category/error.html.twig', ['category' => $category,]);
        }

        return $this->render('category/dislplayCategory.html.twig', ['category' => $category,]);
    }

    /**
     * @Route("/admin/addCategory", name="add_category")
     */
    public function addCategory(Request $request): Response {

        $form = $this->createForm(AddCateroryType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $category = new Category();
            $category->setName($data['name']);
            $category->setDescription($data['description']);

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute("all_category");
        }

        /*$errors = $validator->validate($category);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }*/

        return $this->render('category/addCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/updateCategory/{id}", name="update_category")
     */
    public function updateCategory(Request $request, $id): Response {

        $category = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->find($id);

        if(!$category){
            return $this->render('category/error.html.twig',['error' => 'La categorie n\'existe pas'] );
        }

        $form = $this->createForm(AddCateroryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute("display_category", [
                "id" => $category->getId()]);
        }

        return $this->render('category/editCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/deleteCategory/{id}", name="delete_category")
     */
    public function deleteCategory($id): Response {

        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager
                    ->getRepository(Category::class)
                    ->find($id);
        
        if(!$category){
            return $this->render('category/error.html.twig',['error' => 'La categorie n\'existe pas'] );
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute("all_category");
    }
}