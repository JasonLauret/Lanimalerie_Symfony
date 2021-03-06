<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin')]
class BrandController extends AbstractController
{
    #[Route('/brand', name: 'brand_index')]
    public function allBrand(BrandRepository $brandRepository, PaginatorInterface $paginator, Request $request)
    {
        $brand = $paginator->paginate($brandRepository->findAll(), $request->query->getInt('page', 1), 5);

        return $this->render('brand/index.html.twig', [
            'brands' => $brand,
        ]);
    }

    #[Route('/new', name: 'brand_new')]
    public function new(Request $request, SluggerInterface $slugger)
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Transfomer le nom de l'image
            //1. Je récupère le contenu de mon champ d'upload qui a été envoyé
            $logoFile = $form->get('logo')->getData();
            //2. Si mon champ d'upload n'est pas vide : je vais faire traitement de mon fichier 
            if($logoFile) {
                //3. Je récupère le nom du fichier uploadé (JUSTE le nom du fichier)
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                //4. Je convertis le nom de mon fichier en Slug (nom de fichier sans espace, sans accent)
                $safeFilename = $slugger->slug($originalFilename);
                //5. Création d'un nouveau nom de fichier à partir du slug + un identifiant unique (evite les problemes d'upload de fichiers ayant des noms identiques) + extension du fichier d'origine
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

            return $this->redirectToRoute('brand_index');
        }

        return $this->renderForm('brand/new.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }

    
    #[Route('/brand/{id}', name: 'brand_show')]
    public function show($id)
    {
        $brand = $this->getDoctrine()
                    ->getRepository(Brand::class)
                    ->find($id);
                    
        return $this->render('brand/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    #[Route('/brand/{id}/edit', name: 'brand_edit')]
    public function edit(Request $request, Brand $brand, SluggerInterface $slugger)
    {
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Transfomer le nom de l'image
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
            return $this->redirectToRoute('brand_index');
        }

        return $this->renderForm('brand/edit.html.twig', [
            'brand' => $brand,
            'form' => $form,
        ]);
    }
   
    #[Route('/brand/{id}/delete', name: 'brand_delete')]
    public function deleteBrand($id) {
        $em = $this->getDoctrine()->getManager();
        $brand = $em
                    ->getRepository(Brand::class)
                    ->find($id);
        
        $em->remove($brand);
        $em->flush();

        return $this->redirectToRoute("brand_index");
    }
}
