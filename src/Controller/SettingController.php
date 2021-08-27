<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingController extends AbstractController
{
    #[Route('/setting', name: 'setting')]
    public function setting(): Response
    {
        return $this->render('setting/setting.html.twig', [
            'controller_name' => 'SettingController',
        ]);
    }

    /* #[Route('user/{id}/edit', name: 'edit_user', methods: ['GET', 'POST'])]
    public function editUser(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('setting');
        }

        return $this->renderForm('setting/editUser.html.twig', [
            'form' => $form,
        ]);
    } */

    #[Route('/user/{id}/edit', name: 'edit_user', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('setting/editUser.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

}
