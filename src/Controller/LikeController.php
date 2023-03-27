<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like')]
    #[IsGranted('ROLE_USER')]
    public function index(Post $post, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if ($post->getLikes()->contains($user)) {
            $post->removeLike($user);
        } else {
            $post->addLike($user);
        }

        $manager->flush();

        return $this->json(['content' => $post->getContent()]);
    }
}
