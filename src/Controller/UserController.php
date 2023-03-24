<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
