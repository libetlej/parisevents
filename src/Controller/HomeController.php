<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Afficher la page d'accueil
     *
     * @Route("/", name="home")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }
}