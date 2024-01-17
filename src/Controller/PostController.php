<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\NewPostType;
use App\Repository\NiveauRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{
    #[Route('/post/new', name:'app_post_new')]
    public function newPost(Request $request,EntityManagerInterface $entityManager, SluggerInterface $slugger, PostRepository $postRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(NewPostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $post->setUser($this->getUser());
            $post->setCreatedAt(date_create_immutable());
            $published = $form->get('published')->getData();
            $post->setPublished($published);
            if ($published = true) {
                $post->setPublishedAt(date_create_immutable());
            }
            $post->setTitle($form->get('title')->getData());
            $post->setContent($form->get('content')->getData());
            $post->setNiveau($form->get('niveau')->getData());
            $post->setMatiere($form->get('matiere')->getData());
            if($form->get('image') and $form->get('image')->getData() != null){
                $postImage = $form->get('image')->getData();

                if($postImage){
                    $originalFilename = pathinfo($postImage->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $this->getUser()->getId().'-'.$safeFilename.'.'.$postImage->guessExtension();
                }

                try {
                    $postImage->move(
                        $this->getParameter('post_dir'),
                        $newFilename
                    );
                }catch (FileException $exception){
                    dd($exception);
                }

                if($newFilename) {
                    $post->setImage('/uploads/posts/'.$newFilename);
                } else {
                    $post->setImage(null);
                }
            }

            $entityManager->persist($post);

            $entityManager->flush();

            return new RedirectResponse('/', 302);
        }


        return $this->render('post/newPost.html.twig', [
            'postForm' => $form
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

    #[Route('/post/niveau/{niveauId}', name: 'app_post_niveau')]
    public function byLevel(int $niveauId, PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy(['niveau'=>$niveauId]);
        dd($posts);
    }

    #[Route('/post/publish/{postId}')]
    public function publish(int $postId, PostRepository $postRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $post = $postRepository->find($postId);
        $postStatus = $post->isPublished();

        if ($postStatus){
            $post->setPublished(false);
            $entityManager->persist($post);
        } else {
            $post->setPublished(true);
            $post->setPublishedAt(date_create_immutable());
            $entityManager->persist($post);
        }

        $entityManager->flush();

        return new JsonResponse(['message'=> $post->getTitle().' a été mis à jour'], Response::HTTP_OK);
    }


}
