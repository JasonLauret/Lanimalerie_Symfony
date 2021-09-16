<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\Adress1Type;
use App\Repository\AdressRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adress')]
class AdressController extends AbstractController
{
    #[Route('/', name: 'adress_index', methods: ['GET'])]
    public function allAdress(AdressRepository $adressRepository, CartService $cartService): Response
    {
        $panierWithData = $cartService->getFullCart();

        return $this->render('adress/index.html.twig', [
            'adresses' => $adressRepository->displayAdressById($this->getUser()),
            'items' => $panierWithData,
        ]);
    }

    #[Route('/new', name: 'adress_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $adress = new Adress();
        $form = $this->createForm(Adress1Type::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adress->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adress);
            $entityManager->flush();

            return $this->redirectToRoute('adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adress/new.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'adress_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adress $adress): Response
    {
        $form = $this->createForm(Adress1Type::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adress/edit.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'adress_delete', methods: ['POST'])]
    public function delete(Request $request, Adress $adress): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adress->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adress_index', [], Response::HTTP_SEE_OTHER);
    }
}
