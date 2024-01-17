<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Repository\BookmarkRepository;
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
    public function save(int $postId, EntityManagerInterface $entityManager, BookmarkRepository $bookmarkRepository, PostRepository $postRepository): JsonResponse
    {
        $user = $this->getUser();
        $post = $postRepository->findOneBy(['id' => $postId]);

        $bookmarks = $bookmarkRepository->findOneBy(['user' => $user, 'post' => $postId]);
        if (!$bookmarks){
            $bookmark = new Bookmark();
            $bookmark->setUser($user);
            $bookmark->setPost($post);

            $entityManager->persist($bookmark);
            $message = 'Le post à bien été mis dans votre classeur';
            $bookmarked = true;

        } else {
            $entityManager->remove($bookmarks);
            $bookmarked = false;
            $message = "Le post n'est plus dans votre classeur !";
        }

        $entityManager->flush();


        $bookmarkedCount = $post->getBookmarks()->count();

        return new JsonResponse(['message' => $message, 'bookmarked' => $bookmarked, 'bookmarkedCount' => $bookmarkedCount], Response::HTTP_OK);

    }
}
