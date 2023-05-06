<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commande;
use App\Entity\Lcommande;
use App\Form\CommandeType;
use App\Form\AdresseCommandeType;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\LcommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET','POST'])]
    public function index(CommandeRepository $commandeRepository,ProduitRepository $produitRepository, Request $request): Response
    {
        $session = $request->getSession();
        $carts = $session->get('cart');
        $cartComplet = [];        
        if($carts){
            foreach ($carts as $id => $quantite)
            {
                $produits = $produitRepository->findOneById($id);
                if(!$produits)
                {
                    unset($carts[$id]);
                    $session->set('cart', $carts);
                    continue;
                }
                $cartComplet[] = [
                    'produit' => $produits,
                    'quatite' => $quantite
                ];
            }
        }

       if(!$this->getUser()->getAdresses()->getValues()){
            return $this->redirectToRoute('app_adresse_new');
       }

       $form = $this->createForm(AdresseCommandeType::class, null, [
        'user' => $this->getUser()
       ]);
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
            'form' => $form->createView(),
            'carts' => $cartComplet
        ]);
    }


    #[Route('/valider/order/client', name: 'app_commande_new', methods: ['POST','GET'])]
    public function new(Request $request,EntityManagerInterface $entityManager, CommandeRepository $commandeRepository, ProduitRepository $produitRepository, LcommandeRepository $lcommandeRepository): Response
    {
        $session = $request->getSession();
        $carts = $session->get('cart');
        $cartComplet = [];        
        if($carts){
            foreach ($carts as $id => $quantite)
            {
                $produits = $produitRepository->findOneById($id);
                if(!$produits)
                {
                    unset($carts[$id]);
                    $session->set('cart', $carts);
                    continue;
                }
                $cartComplet[] = [
                    'produit' => $produits,
                    'quatite' => $quantite
                ];
            }
        }

       $form = $this->createForm(AdresseCommandeType::class, null, ['user' => $this->getUser()]);
       $form->handleRequest($request);
        $date= new DateTime();
       // $transporteur = '';
        if ($form->isSubmitted() && $form->isValid())
        {

            //recuperer l'adresse et transporteur venant du recap
            $commande = new Commande(); 
            $transporteur = $form->get('transporteur')->getData();
            $adresses = $form->get('adresses')->getData();

            $content_adresses = $adresses->getNom().''.$adresses->getPrenom();
            $content_adresses .= '</br>'.$adresses->getTelephone();
            if($adresses->getCompagnie()){
                $content_adresses .= '</br>'.$adresses->getCompagnie();
            }

            $content_adresses .= '</br>'.$adresses->getAdresse();
            $content_adresses .= '</br>'.$adresses->getPostale().''.$adresses->getVille();
            $content_adresses .= '</br>'.$adresses->getPays();

           // dd($content_adresses);
            //enregistrer la commande
            $commande->setUser($this->getUser());
            //créer une réference de la commande
            $reference = $date->format('dmY').'-'.uniqid();
            $commande->setReference($reference);
            $commande->setDateCreation($date);
            $commande->setNomTransporteur($transporteur->getNom());
            $commande->setPrixTransporteur($transporteur->getPrix());
            $commande->setAdresse($content_adresses);
            $commande->setIsPaie(0);

            $entityManager->persist($commande);

            //le tableau des produit pour stripe
            //enregistrer les produits dans lcommande comme commande détaille
            foreach($cartComplet as $item){
                $lcommande = new Lcommande();
                $lcommande->setCommande($commande);
                $lcommande->setProduit($item['produit']->getNom());
                $lcommande->setQuatite($item['quatite']);
                $lcommande->setPrix($item['produit']->getPrix());
                $total = $item['quatite'] * $item['produit']->getPrix();
                $lcommande->setTotal($total);
                //$lcommandeRepository->save($lcommande, true);
                $entityManager->persist($lcommande);

            }
           // dd($commande->getReference());
            $entityManager->flush();

            return $this->render('commande/show.html.twig', [
                'carts' => $cartComplet,
                'transporteur' => $transporteur,
                'livraisons' => $content_adresses,
                'reference' => $commande->getReference()
            ]);
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $commandeRepository->remove($commande, true);
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
