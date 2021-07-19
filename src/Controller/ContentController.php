<?php
// src/Controller/ContentController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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

    
    public function home(): Response {
        return $this->render('content/home.html.twig');
    }

    
    public function registration(): Response {
        return $this->render('content/registration.html.twig');
    }

    
    public function pageLogin(): Response {
        return $this->render('content/pageLogin.html.twig');
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function setting(): Response {
        return $this->render('content/setting.html.twig');
    }

}