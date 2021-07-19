<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddUserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserManagementController extends AbstractController
{
    #[Route('/user/management', name: 'user_management')]
    public function index(): Response
    {
        return $this->render('user_management/index.html.twig', [
            'controller_name' => 'UserManagementController',
        ]);
    }

    /**
     * @Route("/admin/user", name="user")
     */
    public function allUser()
    {
        $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findAll();

        return $this->render('user_management/allUser.html.twig', [
            'users' => $user,
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="display_user")
     */
    public function displayUser($id)
    {
        $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->find($id);

        if (!$user){
            //throw $this->createNotFoundException("La catégorie demandée n'existe pas");
            return $this->render('user_management/error.html.twig', ['user' => $user,]);
        }

        return $this->render('user_management/displayUser.html.twig', ['user' => $user,]);
    }


    /**
     * @Route("/admin/addUser", name="add_user")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(AddUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_management/addUser.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/updateUser/{id}", name="update_user")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(AddUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_management/addUser.html.twig', [
            'brand' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/deleteUser/{id}", name="delete_user")
     */
    public function deleteUser($id): Response {

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager
                    ->getRepository(User::class)
                    ->find($id);
        
        if(!$user){
            return $this->render('user_management/error.html.twig',['error' => 'La categorie n\'existe pas'] );
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute("user");
    }
}
