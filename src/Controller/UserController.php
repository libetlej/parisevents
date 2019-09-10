<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Favorite;
use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\PasswordUpdateType;
use App\Form\RegisterType;
use App\Form\UserType;
use App\Repository\EventRepository;
use App\Repository\FavoriteRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * Connexion de l'utilisateur
     *
     * @Route("/login", name="user_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        //dump($error);

        $username = $utils->getLastUsername();

        return $this->render('user/login.html.twig', [
            // Si error different de null
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Déconnexion
     *
     * @Route("/logout", name="user_logout")
     */
    public function logout() {

    }

    /**
     * Inscription
     *
     * @Route("/register", name="user_register")
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encoder le mot de passe
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte à bien été crée"
            );

            return $this->redirectToRoute('user_login');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Modification du profile
     *
     * @Route("/user/edit", name="user_edit")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Request $request, ObjectManager $manager) {

        $user = $this->getUser();
        // recuperer l'utilisateur connecté avec getUser()
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Il est normalement pas nécessaire de persister une entité qui existe déjà
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Vos informations ont bien été modifiées."
            );
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Modification du mot de passe
     *
     * @Route("user/password", name="user_password")
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ObjectManager$manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {

        $passwordUpdate = new PasswordUpdate();

        // recuperer l'utilisateur connecté pour pouvoir vérifier si il à saisie le bon ancien mot de passe
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Vérifier le oldPassword /
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                $form->get('oldPassword')->addError(new FormError("Ancien mot de passe incorrect"));
            } else {
                // encoder et enregistrer
                $newPassword = $passwordUpdate->getNewPassword();
                $password = $encoder->encodePassword($user, $newPassword);
                $user->setPassword($password);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe à bien été modifié."
                );

            return $this->redirectToRoute('user_edit');

            }
        }

        return $this->render('user/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Afficher les événements favoris
     *
     * @Route("/user/favorite", name="user_favorite")
     * @IsGranted("ROLE_USER")
     *
     * @param FavoriteRepository $favoriteRepository
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function userFavorite(FavoriteRepository $favoriteRepository, EventRepository $eventRepository) {

        $user = $this->getUser();
        $favorites = $favoriteRepository->findBy(array('user' => $user));

        $events = $eventRepository->findBy(['user' => $user]);

        return $this->render('user/favorite.html.twig', [
            'favorites' => $favorites,
            'events' => $events
        ]);
    }

    /**
     * Afficher le profile d'un utilisateur
     *
     * @Route("/user/{id}", name="user_profile")
     *
     * @param User $user
     * @return Response
     */
    public function profile(User $user) {

        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);

    }
}
