<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(SessionInterface $sessionInterface): Response
    {
        $carts = $sessionInterface->get('cart');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'carts' => $carts
        ]);
    }
}
