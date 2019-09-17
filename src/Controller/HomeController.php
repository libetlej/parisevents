<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
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
     * @param EventRepository $eventRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(EventRepository $eventRepository, CategoryRepository $categoryRepository)
    {
        $newEvents      = $eventRepository->findNewEvents();
        $currentEvents  = $eventRepository->findCurrentEvents();

        return $this->render('index.html.twig', [
            'currentEvents' => $currentEvents,
            'newEvents'     => $newEvents,
            'categories'    => $categoryRepository->findAll()
        ]);
    }

    /**
     * Afficher tous les événements d'une catégorie
     *
     * @Route("/category/{category}", name="category")
     *
     * @param Category $category
     * @return Response
     */
    public function category(Category $category) {

        return $this->render('category.html.twig', [
            'category' => $category
        ]);
    }
}