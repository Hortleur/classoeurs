<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookmarksController extends AbstractController
{
    #[Route('/bookmarks/save/{postId}', name: 'bookmarks_save')]
    public function save(int $postId, PostRepository $postRepository, EntityManagerInterface $entityManager, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($this->getUser());

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], Response::HTTP_UNAUTHORIZED);
        }

        $post = $postRepository->find($postId);

        if (!$post) {
            return new JsonResponse(['message' => 'Post introuvable'], Response::HTTP_NOT_FOUND);
        }

        $bookmark = new Bookmark();
        $bookmark->setUser($user);
        $bookmark->setPost($post);

        $entityManager->persist($bookmark);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Post sauvegarder dans votre classeur'], Response::HTTP_OK);

    }
}
