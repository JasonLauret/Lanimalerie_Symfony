<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressAdminType;
use App\Repository\AdressRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/adress')]
class AdressAdminController extends AbstractController
{
    #[Route('/', name: 'adress_admin_index', methods: ['GET'])]
    public function index(AdressRepository $adressRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $adress = $paginator->paginate($adressRepository->findAll(), $request->query->getInt('page', 1), 10);

        return $this->render('adress_admin/index.html.twig', [
            'adresses' => $adress,
        ]);
    }

    #[Route('/new', name: 'adress_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $adress = new Adress();
        $form = $this->createForm(AdressAdminType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adress);
            $entityManager->flush();

            return $this->redirectToRoute('adress_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adress_admin/new.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'adress_admin_show', methods: ['GET'])]
    public function show(Adress $adress): Response
    {
        return $this->render('adress_admin/show.html.twig', [
            'adress' => $adress,
        ]);
    }

    #[Route('/{id}/edit', name: 'adress_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adress $adress): Response
    {
        $form = $this->createForm(AdressAdminType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('adress_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adress_admin/edit.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'adress_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Adress $adress): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adress->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adress_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
