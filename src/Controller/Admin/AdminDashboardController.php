<?php

namespace App\Controller\Admin;

use App\Service\Stats;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminDashboardController
 *
 * @Route("/admin")
 *
 * @package App\Controller\Admin
 */
class AdminDashboardController extends AbstractController
{
    /**
     * Afficher le tableau de bord de l'administration
     *
     * @Route("/", name="admin_dashboard")
     *
     * @param ObjectManager $manager
     * @param Stats $stats
     * @return Response
     */
    public function index(ObjectManager $manager, Stats $stats)
    {
        $users      = $stats->getCount( 'user');
        $events     = $stats->getCount( 'event');
        $comments   = $stats->getCount( 'comment');

        $lastUsers      = $stats->getLast('user');
        $lastEvents     = $stats->getLast( 'event');
        $lastComments   = $stats->getLast( 'comment');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => compact('users', 'events', 'comments'),
            'lasts' => compact('lastUsers', 'lastEvents', 'lastComments')
        ]);
    }
}
