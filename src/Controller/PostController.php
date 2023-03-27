<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/publication/{id}', name: 'app_post')]
    public function index(Request $request, Post $post, EntityManagerInterface $manager): Response
    {
        $form = null;

        if ($this->getUser()) {
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setPost($post);
                $comment->setCreator($this->getUser());

                $manager->persist($comment);
                $manager->flush();

                return $this->redirectToRoute('app_post', ['id' => $post->getId()]);
            }
        }

        return $this->render('post/index.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }
}
