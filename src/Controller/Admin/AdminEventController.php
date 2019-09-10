<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminEventController
 *
 * @Route("/admin")
 *
 * @package App\Controller\Admin
 */
class AdminEventController extends AbstractController
{
    /**
     * Afficher tous les événements
     *
     * @Route("/event/{page<\d+>?1}", name="admin_event_index")
     *
     * @param EventRepository $eventRepository
     * @param $page
     * @param Pagination $pagination
     * @return Response
     */
    public function index(EventRepository $eventRepository, $page, Pagination $pagination)
    {
        $pagination->setEntityClass(Event::class);
        $pagination->setCurrentPage($page);

        return $this->render('admin/event/index.html.twig', [
            'events'  => $pagination->getData(),    // $repository->findBy([], [], $this->limit, $offset);
            'pages'   => $pagination->getPages(),   // Le nombre de page
            'page'    => $page                      // La page actuelle
        ]);
    }

    /**
     * Editer un événement
     *
     * @Route("/event/{id}/edit", name="admin_event_edit")
     *
     * @param Event $event
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Event $event, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($event);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'événement <strong>{$event->getTitle()}</strong> à bien été modifié."
            );
        }

        return $this->render('admin/event/edit.html.twig', [
            'event' => $event,
            'form'  => $form->createView()
        ]);
    }

    /**
     * Supprimer un événement
     *
     * @Route("/event/{id}/delete", name="admin_event_delete")
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
            "L'événement à bien été supprimé."
        );

        return $this->redirectToRoute('admin_event_index');
    }
}
