<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Produit;
use App\Entity\Commande;
use Stripe\Checkout\Session;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/stripe/checkout/{reference}', name: 'app_stripe', methods: ['POST', 'GET'])]
    public function index(EntityManagerInterface $entityManager, ProduitRepository $produitRepository, SessionInterface $session, $reference): Response
    {

        $commande = $entityManager->getRepository(Commande::class)->findOneByReference($reference);
        //$session = $request->getSession();
      /* $carts = $session->get('cart');
        $cartComplet = [];   
        */    
        /**
         * création du paiement via la carte visa
         * 1 - on initialise la variable
         * 2 - maintenat créer une checkout session qui permettra de prendre nos variable de commande
         * 3 - envoyer l'id de $checkout session à la vue twig
         * 4 - ajouter le js.stripe.com/v3 dans la view show.html.twig
         * 5 - ajouter le id="checkout_button" sur notre bouton payer pour que stripe ecoute notre page
         * 6 - après pour ajouter le montant de la livraison il faut créer une réference sur la commande dans le controleur commande
         */

        //le tableau des produit pour stripe
        $produit_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        foreach($commande->getLcommandes()->getValues() as $item)
        {
            //remplier le tableau dans le tableau stripe
            $produit = $entityManager->getRepository(Produit::class)->findOneByNom($item->getProduit());
          //  dd($produit);
            $produit_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $item->getPrix(),
                'product_data' => [
                    'name' => $item->getProduit(),
                    'images' => [$YOUR_DOMAIN."/image_produits/".$produit->getIllustration()]
                ]
                ],
                'quantity' => $item->getQuatite()
                    
            ];
        }

        //les donneés du livreur
        $produit_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $commande->getPrixTransporteur()*100,
                'product_data' => [
                    'name' => $commande->getNomTransporteur(),
                    'images' => [$YOUR_DOMAIN]
                ]
                ],
                'quantity' => 1
                    
        ];

       // dd($produit_stripe);

    //le 1
    $key = 'sk_test_51N4MxhDVdir5HzlHn3NbxLDcQYIX2LjOkrZvrxz3ZpVMydfMTjhS7sfnanYtSa3hpG96LlEk6E0tnffEXOFSaT0K00plWH7KnO';
    Stripe::setApiKey($key);


    $checkout_session = Session::create([
        'customer_email' => $this->getUser()->getEmail(),
        'payment_method_types' => ['card'],
        'line_items' => [$produit_stripe],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/success.html',
        'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
      ]);


        $response = new Response('', Response::HTTP_SEE_OTHER);
        $response->headers->set('Location', $checkout_session->url);

        return $response;
    }
} 
