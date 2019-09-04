<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request, ObjectManager $manager) {

        $event = new Event();

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //$manager = $this->getDoctrine()->getManager();
            $manager->persist($event);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'événement {$event->getTitle()} a bien été enregistré."
            );

            return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
        }


        return $this->render('event/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Editer un événement
     *
     * @Route("/{id}/edit", name="event_edit")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Request $request, ObjectManager $manager, Event $event) {

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($event);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'événement {$event->getTitle()} a bien été modifié."
            );

            return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
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

    /**
     * Supprimer un événement
     *
     * @Route("/{id}/delete", name="event_delete")
     *
     * @param Event $event
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Event $event, ObjectManager $manager) {

        $manager->remove($event);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'événement a bien été supprimé"
        );

        return $this->redirectToRoute('event_index');
    }

}
