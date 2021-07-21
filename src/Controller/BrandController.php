<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class BrandController extends AbstractController
{
    #[Route('/admin/brand', name: 'brand_index', methods: ['GET'])]
    public function index(BrandRepository $brandRepository): Response
    {
        return $this->render('brand/index.html.twig', [
            'brands' => $brandRepository->findAll(),
        ]);
    }

    #[Route('/admin/new', name: 'brand_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //1. Je récupère le contenu de mon champ d'upload qui a été envoyé
            $logoFile = $form->get('logo')->getData();

            //2. Si mon champ d'upload n'est pas vide : je vais faire traitement de mon fichier 
           if($logoFile) {
                //3. Je récupère le nom du fichier uploadé (JUSTE le nom du fichier)
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);

                //4. Je convertis le nom de mon fichier en Slug (nom de fichier sans espace, sans accent = utilisable dans une URL)
                $safeFilename = $slugger->slug($originalFilename);

                //5. Création d'un nouveau de fichier à partir du slug + un identifiant unique (evite les problemes d'upload de fichiers ayant des noms identiques) + extension du fichier d'origine
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();

                try{

                    //6.Copie du fichier uploadé qui est temporairement stocké quelque part dans sur le serveur avec renommage en utilisant le nouveau nom
                    $logoFile->move($this->getParameter('upload_directory'), $newFilename);
                }
                catch (FileException $e) {

                    var_dump($e);
                    die('Erreur' );
                }

                $brand->setLogo($newFilename);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($brand);
            $entityManager->flush();

            return $this->redirectToRoute('brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('brand/new.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin/brand/{id}', name: 'brand_show', methods: ['GET'])]
    public function show(Brand $brand): Response
    {
        return $this->render('brand/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    #[Route('/admin/brand/{id}/edit', name: 'brand_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Brand $brand, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logoFile = $form->get('logo')->getData();

           if($logoFile) {
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();

                try{
                    $logoFile->move($this->getParameter('upload_directory'), $newFilename);
                }
                catch (FileException $e) {
                    var_dump($e);
                    die('Erreur' );
                }

                $brand->setLogo($newFilename);

            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('brand_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('brand/edit.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    #[Route('/admin/brand/{id}', name: 'brand_delete', methods: ['POST'])]
    public function delete(Request $request, Brand $brand): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($brand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('brand_index', [], Response::HTTP_SEE_OTHER);
    }
}
