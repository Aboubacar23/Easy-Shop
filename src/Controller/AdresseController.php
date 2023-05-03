<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adresse')]
class AdresseController extends AbstractController
{
    #[Route('/', name: 'app_adresse_index', methods: ['GET'])]
    public function index(AdresseRepository $adresseRepository): Response
    {
        //dd($this->getUser());
        return $this->render('adresse/index.html.twig', [
            'adresses' => $adresseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_adresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdresseRepository $adresseRepository): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        $session = $request->getSession();
        $cart = $session->get('cart');
       // dd($cart);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setUser($this->getUser());
            $adresseRepository->save($adresse, true);
            if($cart){
                return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
            }else{
                return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('adresse/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adresse_show', methods: ['GET'])]
    public function show(Adresse $adresse): Response
    {
        return $this->render('adresse/show.html.twig', [
            'adresse' => $adresse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adresse $adresse, AdresseRepository $adresseRepository): Response
    {
        if(!$adresse || $adresse->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_adresse_index');
        }
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresseRepository->save($adresse, true);

            return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_adresse_delete', methods: ['GET','POST'])]
    public function delete(Request $request, Adresse $adresse, AdresseRepository $adresseRepository): Response
    { 
        if($adresse || $adresse->getUser() == $this->getUser()){
            $adresseRepository->remove($adresse, true);
        }

        return $this->redirectToRoute('app_adresse_index', [], Response::HTTP_SEE_OTHER);
    }
}
