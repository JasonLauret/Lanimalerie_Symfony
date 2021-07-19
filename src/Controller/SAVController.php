<?php

namespace App\Controller;

use App\Form\SavType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SAVController extends AbstractController
{
    #[Route('/sav', name: 'sav')]
    public function index(): Response
    {
        $form = $this->createForm(SavType::class);

        return $this->render('sav/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
