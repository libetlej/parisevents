<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminUserController
 *
 * @Route("/admin")
 *
 * @package App\Controller\Admin
 */
class AdminUserController extends AbstractController
{
    /**
     * Afficher tous les utilisateurs
     *
     * @Route("/user/{page<\d+>?1}", name="admin_user_index")
     *
     * @param UserRepository $userRepository
     * @param $page
     * @param Pagination $pagination
     * @return Response
     */
    public function index(UserRepository $userRepository, $page, Pagination $pagination) {
        $pagination->setEntityClass(User::class);
        $pagination->setCurrentPage($page);

        return $this->render("admin/user/index.html.twig", [
            'users'   => $pagination->getData(),
            'pages'   => $pagination->getPages(),
            'page'    => $page
        ]);
    }

    /**
     * Editer un utilisateur
     *
     * @Route("/user/{id}/edit", name="admin_user_edit")
     *
     * @param User $user
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(User $user, Request $request, ObjectManager $manager) {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur à bien été modifié."
            );
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Supprimer un utilisateur
     *
     * @Route("/user/{id}/delete", name="admin_user_delete")
     *
     * @param User $user
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(User $user, ObjectManager $manager) {
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'utilisateur à bien été supprimé."
        );

        return $this->redirectToRoute('admin_user_index');
    }
}