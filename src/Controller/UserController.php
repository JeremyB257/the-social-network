<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    #[Route('/membres', name: 'app_user')]
    #[IsGranted('ROLE_USER')]
    public function index(UserRepository $repository): Response
    {
        return $this->render('user/index.html.twig', [
            'members' => $repository->findAll(),
        ]);
    }

    #[Route('/membre/{username}', name: 'app_user_show')]
    public function show(Request $request, User $user, EntityManagerInterface $manager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreator($this->getUser());

            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('app_user_show', ['username' => $this->getUser()->getUsername()]);
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/membre/profil/modifier', name: 'app_user_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $manager
    ) {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        // Evite le bug de "logout"
        if ($form->isSubmitted() && !$form->isValid()) {
            $manager->refresh($user);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // Pseudo (@todo bug when double ?)
            $user->setUsername($slugger->slug($user->getFirstname())->lower());

            // @todo Upload

            $manager->flush();

            return $this->redirectToRoute('app_user_show', ['username' => $user->getUsername()]);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form,
            'user' => $this->getUser(),
        ]);
    }
}
