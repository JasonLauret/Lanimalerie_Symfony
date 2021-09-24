<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditPasswordType;
use App\Form\EditUserType;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/setting/edit')]
class SettingUserController extends AbstractController
{

    #[Route('/', name: 'setting_user_index', methods: ['GET'])]
    public function allAdress(UserRepository $userRepository): Response
    {
        return $this->render('setting_user/index.html.twig', [
            'users' => $userRepository->displayUser($this->getUser()),
        ]);
    }

    #[Route('/{id}', name: 'setting_user_edit', methods: ['GET', 'POST'])]
    public function editUser(Request $request, User $user): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('setting_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('setting_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/password/{id}', name: 'edit_password', methods: ['GET', 'POST'])]
    public function editPassword(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(EditPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('setting_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('setting_user/editPassword.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
