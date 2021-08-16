<?php
// src/Controller/ContentController.php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;


class ContentController extends AbstractController
{
    public function cgv(string $title): Response {
        return $this->render('content/cgv.html.twig', [
            'title' => $title,
            'description' => 'Contenu de la page',
        ]);
    }


    public function mentionLegal(string $title): Response {
        return $this->render('content/mentionLegal.html.twig', [
            'title' => $title,
            'description' => 'Contenu de la page',
        ]);
    }


    public function politique(string $title): Response {
        return $this->render('content/politique.html.twig', [
            'title' => $title,
            'description' => 'Contenu de la page',
        ]);
    }


    public function registration(): Response {
        return $this->render('content/registration.html.twig');
    }


    public function pageLogin(): Response {
        return $this->render('content/pageLogin.html.twig');
    }


    public function setting(): Response {
        return $this->render('content/setting.html.twig');
    }

    #[Route('/delivery', name: 'delivery')]
    public function displayOrder(): Response {
        return $this->render('content/delivery.html.twig');
    }
}