<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\Favorite;
use App\Form\CommentType;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\FavoriteRepository;
use App\Service\FileUploader;
use App\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EventController extends AbstractController
{
    /**
     * Afficher tous les événements disponibles
     *
     * @Route("/events/{page<\d+>?1}", name="event_index")
     *
     * @param EventRepository $eventRepository
     * @param $page
     * @param Pagination $pagination
     * @return Response
     */
    public function index(EventRepository $eventRepository, $page, Pagination $pagination)
    {
        //$this->getDoctrine()->getRepository(EventRepository::class);

        $pagination->setEntityClass(Event::class);
        $pagination->setLimit(12);
        $pagination->setCurrentPage($page);

        return $this->render('event/index.html.twig', [
            'events'  => $pagination->getData(),
            'pages'   => $pagination->getPages(),
            'page'    => $page
        ]);
    }

    /**
     * Créer un événement
     *
     * @Route("/event/new", name="event_create")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager) {

        $event = new Event();
        $user = $this->getUser();

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form['image']->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = preg_replace('/[^A-Za-z0-9\-]/', "", $originalFilename);
                $newFilename = $safeFilename .'-'. uniqid() .'.'. $imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('events_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $event->setImage($newFilename);
            }

            //$manager = $this->getDoctrine()->getManager();
            $event->setUser($user);

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
     * Afficher les détails d'un événement
     *
     * @Route("/event/{id}", name="event_show")
     *
     * @param Event $event
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function show(Event $event, Request $request, ObjectManager $manager) {

        $user = $this->getUser();
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($user);
            $comment->setEvent($event);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire à bien été pris en compte"
            );
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * Supprimer un commentaire
     *
     * @Route("/comment/{id}/delete", name="comment_delete")
     * @Security("is_granted('ROLE_USER') and user === event.getUser()")
     *
     * @param Event $event
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function deleteComment(Event $event, Comment $comment, ObjectManager $manager) {
        $manager->remove($comment);
        $manager->flush();

        return $this->redirectToRoute('event_show', ['id' => $comment->getEvent()->getId()]);
    }

    /**
     * Editer un événement
     *
     * @Route("/event/{id}/edit", name="event_edit")
     * @Security("is_granted('ROLE_USER') and user === event.getUser()")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param Event $event
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function edit(Request $request, ObjectManager $manager, Event $event, FileUploader $fileUploader) {

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form['image']->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = preg_replace('/[^A-Za-z0-9\-]/', "", $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('events_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $event->setImage($newFilename);
            }

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
     * Supprimer un événement
     *
     * @Route("/event/{id}/delete", name="event_delete")
     * @Security("is_granted('ROLE_USER') and user == event.getUser()")
     *
     * @param Event $event
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Event $event, ObjectManager $manager) {

        unlink($event->getEventImageFile());
        $manager->remove($event);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'événement a bien été supprimé"
        );

        return $this->redirectToRoute('event_index');
    }

    /**
     * Ajouter aux favoris un événement
     *
     * @Route("/event/{id}/favorite", name="event_favorite")
     *
     */
    public function favorite(Event $event, ObjectManager $manager, FavoriteRepository $favoriteRepository)
    {
        $user = $this->getUser();

        // Si pas connecté
        if(!$user) return $this->json([
            'code'    => 403,
            'message' => 'il faut connexion'
        ], 403);

        // Si user like
        if($event->isFavorite($user)) {
            $favorite = $favoriteRepository->findOneBy([
                'event' => $event,
                'user' => $user
            ]);
            $manager->remove($favorite);
            $manager->flush();

            return $this->json([
                'code'          => 200,
                'message'       => "Like supprimé",
            ], 200);
        }

        // Si user pas de like
        $favorite = new Favorite();
        $favorite   ->setEvent($event)
                    ->setUser($user);
        $manager->persist($favorite);
        $manager->flush();

        return $this->json([
            'code'    => 200,
            'message' => "Ajouté aux favoris",
        ], 200);
    }

}
