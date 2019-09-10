<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class AdminDashboardController
 *
 * @Route("/admin")
 *
 * @package App\Controller\Admin
 */
class AdminAccountController extends AbstractController
{
    /**
     * Connexion à l'administration
     *
     * @Route("/login", name="admin_account_login")
     *
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Déconnexion de l'administration
     *
     * @Route("/logout", name="admin_account_logout")
     *
     * @return void
     */
    public function logout()
    {

    }
}
