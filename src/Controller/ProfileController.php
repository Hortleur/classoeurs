<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\UsernameFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use function PHPUnit\Framework\isNull;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request,UserRepository $userRepository,SluggerInterface $slugger, Filesystem $filesystem): Response
    {

        $actualUser = $this->getUser()->getUserIdentifier();
        $oldProfilePic = $this->getUser()->getProfile()->getProfilePhoto();

        $profile = new Profile();
        $form = $this->createForm(UsernameFormType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('imageFile')){
                $profilePhoto = $form->get('imageFile')->getData();

                if ($profilePhoto){
                    $originalFilename = pathinfo($profilePhoto->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $this->getUser()->getId().'-'.$safeFilename.'.'.$profilePhoto->guessExtension();

                    try {
                        $profilePhoto->move(
                            $this->getParameter('profiles_dir'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        dd($e);
                    }
                }
            }
            if ($newFilename){
                $profile->setProfilePhoto('/uploads/profiles/'.$newFilename);
            }
            if ($form->get('bio')){
                $profile->setBio($form->get('bio')->getData());
            }
            if ($form->get('niveau')){
                $profile->setNiveau($form->get('niveau')->getData());
            }
            if ($form->get('username')){
                $profile->setUsername($form->get('username')->getData());
            }

            $userRepository->updateProfile($actualUser, $profile);

            if ($newFilename !== $originalFilename){
                unlink($oldProfilePic);
            }

            $this->redirectToRoute('app_profile');
        }
        return $this->render('profile/index.html.twig', [
            'UsernameForm' => $form,
        ]);
    }

    #[Route('/profile/{id}', name: 'app_user_profile')]
    public function userProfile(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        $posts = $user->getPosts();

        return $this->render('profile/user.html.twig', [
            "user" => $user,
            'posts' => $posts
        ]);

    }
}
