<?php

namespace App\Entity;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{

    private $session;
    private $entityManager;

    public function __construct(SessionInterface $session,EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }


    public function add($id)
    {
        $this->session->set('cart', [
            [
                'id' => $id,
                'quantite' => 1
            ]
        ]);
    }

    public function get()
    {
        return $this->session->get('cart');
    }
    
    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function getAllCart()
    {
        $carts = $this->get();
        $cartComplet = [];
        
        if($carts){
            foreach ($carts as $id => $quantite)
            {
                $produits = $this->entityManager->getRepository(Produit::class)->findOneById($id);
                if(!$produits)
                {
                    unset($carts[$id]);
                    $this->session->set('cart', $carts);
                    continue;
                }
                $cartComplet[] = [
                    'produit' => $produits,
                    'quatite' => $quantite
                ];
            }
        }

        return $cartComplet;
    }
}