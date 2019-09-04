<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EventController
 *
 * @Route("/event")
 *
 * @package App\Controller
 */
class EventController extends AbstractController
{
    /**
     * Afficher tous les événements disponibles
     *
     * @Route("/", name="event_index")
     *
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function index(EventRepository $eventRepository)
    {
        $events = $eventRepository->findAll();

        return $this->render('event/index.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * Créer un événement
     *
     * @Route("/new", name="event_create")
     *
     * @return Response
     */
    public function create() {

        $event = new Event();

        $form = $this->createForm(EventType::class, $event);

        return $this->render('event/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Afficher les détails d'un événement
     *
     * @Route("/{id}", name="event_show")
     *
     * @param Event $event
     * @return Response
     */
    public function show(Event $event) {

        return $this->render('event/show.html.twig', [
            'event' => $event
        ]);
    }

}
