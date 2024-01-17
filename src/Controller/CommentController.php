<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/new', name: 'app_comment')]
    public function newComment(Request $request, EntityManagerInterface $entityManager, PostRepository $postRepository): JsonResponse
    {
        $postId = $request->query->get('postId');
        $comment = $request->query->get('comment');

        if ($postId === null || $comment === null) {
            return $this->json('Une erreur est survenue, veuillez réessayer plus tard', 500);
        }

        if (empty($postId) || empty($comment)) {
            return $this->json('Les champs postId et comment ne peuvent pas être vides', 400);
        }

        $post = $postRepository->find($postId);

        if (!$post) {
            return $this->json('Le post correspondant à l\'ID fourni n\'existe pas', 404);
        }

        $new_comment = new Commentaire();

        $new_comment->setPost($post);
        $new_comment->setContent($comment);
        $new_comment->setCreatedAt(date_create_immutable());
        $new_comment->setUser($this->getUser());

        $entityManager->persist($new_comment);
        $entityManager->flush();

        return $this->json('Commentaire bien envoyé', 201);
    }

    #[Route('/comment/delete/{commentId}', name:'app_comment_delete')]
    public function deleteComment(int $commentId, CommentaireRepository $commentaireRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($commentId === null){
            return $this->json('Une erreur est survenue, veuillez réessayer plus tard.', 500);
        }

        $comment = $commentaireRepository->findOneBy(['id' => $commentId]);

        if ($comment->getUser() !== $this->getUser() and $this->getUser()->getRoles() != 'ADMIN'){
            return $this->json('Vous ne pouvez pas supprimer ce commentaire', 400 );
        }

        $entityManager->remove($comment);

        $entityManager->flush();

        return $this->json('Commentaire supprimer', 200);
    }
}