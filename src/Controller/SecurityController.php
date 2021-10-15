<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgotten-password', name: 'app_forgotten_password')]
    public function forgottenPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator){
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // On récupère les donnée du formulaire
            $data = $form->getData();
            // On regarde si un utilisateur a cette email
            $user = $userRepository->findOneByEmail($data['email']);
            // Si il n'y pas d'utilisateur avec cette email on renvoie une erreur et on redirige vers la page de login
            if(!$user){
                $this->addFlash('dangerEmail', 'Cette email n\'éxiste pas');
                $this->redirectToRoute('app_login');
            }
            //On génère un token
            $token = $tokenGenerator->generateToken();
            // Si il y a pas d'erreur on ecrit en bdd
            // Si il y a une erreur on affiche un message d'erreur avec une redirection vers la page login
            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

            } catch(\Exception $e){
                $this->addFlash('warning', 'Une erreur est survenue : '. $e->getMessage());
                $this->redirectToRoute('app_login');
            }
            // On génère l'URL de réinitialisation de mot de passe
            $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            // On envoie le message
            $newEmail = (new TemplatedEmail())
                ->from($_ENV['EMAIL_FROM'])
                ->to('lauret.jason73390@gmail.com')
                //->to($user->getEmail())
                ->subject('Réinitialisation de mon mot de passe')
                ->htmlTemplate('security/emailForgottenPassword.html.twig')
                ->context([
                    'url' => $url,
                ])
            ;
            $mailer->send($newEmail);

            // Message flash de confirmation d'envoie
            $this->addFlash('emailEnvoyer', 'Un e-mail de réinitialisation de mot de passe vous a été envoyé.');
            
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgettenPassword.html.twig', [
            'emailForm' => $form->createView(),
        ]);
    }

    #[Route('/resetPassword', name: 'app_reset_password')]
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);

        if(!$user){
            $this->addFlash('danger', 'Le token est inconnu');
            return $this->redirectToRoute('app_login');
        }
        // Si le formulaire est envoyer en methode post
        if($request->isMethod('POST')){
            // On supprime le token
            $user->setResetToken(null);
            //On va chiffrer le mot de passe
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user, $request->request->get('password')));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('forgottenPassword', 'Mot de passe modifié avec succès');

            return $this->redirectToRoute('app_login');

        }else {
            return $this->render('security/restPassword.html.twig', ['token' => $token]);
        }

    }
}
