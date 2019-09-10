<?php


namespace App\Controller\Admin;


use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminCommentController
 *
 * @Route("/admin")
 *
 * @package App\Controller\Admin
 */
class AdminCommentController extends AbstractController
{
    /**
     * @Route("/comment/{page<\d+>?1}", name="admin_comment_index")
     *
     * @param CommentRepository $commentRepository
     * @param $page
     * @param Pagination $pagination
     * @return Response
     */
    public function index(CommentRepository $commentRepository, $page, Pagination $pagination) {
        $pagination->setEntityClass(Comment::class);
        $pagination->setCurrentPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'comments'  => $pagination->getData(),
            'pages'     => $pagination->getPages(),
            'page'      => $page
        ]);
    }

    /**
     * Supprimer un commentaire
     *
     * @Route("/comment/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Comment $comment, ObjectManager $manager) {

        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire à bien été supprimé."
        );

        return $this->redirectToRoute('admin_comment_index');
    }
}