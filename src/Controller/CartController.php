<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart', methods: ['Get', 'POST'])]
    public function index(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $carts = $session->get('cart');
        $cartComplet = [];
        foreach ($carts as $id => $quantite)
        {
            $cartComplet[] = [
                'produit' => $produitRepository->findOneById($id),
                'quatite' => $quantite
            ];
        }

        //dd($cartComplet);
      //  $session->clear();
        return $this->render('cart/index.html.twig', [
            'carts' => $cartComplet,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_add_cart')]
    public function create($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/supprimer/{id}', name: 'app_delete_cart')]
    public function delete($id, SessionInterface $session)
    {
        $cart = $session->get('cart');
        unset($cart[$id]);

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }



    #[Route('/descrase/{id}', name: 'app_descrase_cart')]
    public function descrase($id, SessionInterface $session)
    {
        $cart = $session->get('cart');

        if($cart[$id] > 1){
            $cart[$id]--;
        }
        else{
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart');
    }

}
