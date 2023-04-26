<?php

namespace App\Controller;

use App\Entity\Filtre;
use App\Form\FiltreType;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/nos-produit', name: 'app_product' , methods: ['POST', 'GET'])]
    public function index(ProduitRepository $produitRepository, Request $request): Response
    {
        $filtre = new Filtre();
        $produits = $produitRepository->findAll();

        $form = $this->createForm(FiltreType::class, $filtre);
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {

            $produits = $produitRepository->findByFiltre($filtre);
        }

        return $this->render('product/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/show-produit/{slug}', name: 'app_show_product')]
    public function show($slug, ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->findOneBySlug($slug);        
        if(!$produit){
            return $this->redirectToRoute('app_product');
        }
        return $this->render('product/show.html.twig', [
            'produit' => $produit,
        ]);
    }
}
