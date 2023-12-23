<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/post/{postId}', name: 'app_post_unique')]
    public function show(int $postId,PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBy(['id'=> $postId]);

        return $this->render('post/onepost.html.twig', [
           'post' => $post
        ]);
    }
}
