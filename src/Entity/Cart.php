<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
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
}