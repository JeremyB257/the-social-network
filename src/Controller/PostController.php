<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/api/comment/create/{id}', name: 'app_api_comment_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function create(
        Request $request,
        DateTimeFormatter $dateTimeFormatter,
        Packages $packages,
        EntityManagerInterface $manager,
        Post $post
    ) {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, ['csrf_protection' => false]);
        $form->submit(json_decode($request->getContent(), true));
        // $form = $this->createFormBuilder(null, ['csrf_protection' => false])
        //     ->add('name', null, [
        //         'constraints' => [new NotBlank()]
        //     ])->getForm();

        if (!$form->isValid()) {
            $errors = [];

            foreach ($form->getErrors(true) as $error) {
                $errors[$error->getOrigin()->getName()] = $error->getMessage();
            }

            return $this->json($errors, 422);
        }

        $comment->setPost($post);
        $comment->setCreator($this->getUser());
        $manager->persist($comment);
        $manager->flush();

        return $this->json([
            'message' => 'Merci !',
            'comment' => [
                'content' => $comment->getContent(),
                'avatar' => $comment->getCreator() ? $packages->getUrl($comment->getCreator()->getAvatar()) : null,
                'firstname' => $comment->getCreator() ? $comment->getCreator()->getFirstname() : null,
                'created_at' => $dateTimeFormatter->formatDiff($comment->getCreatedAt(), new \DateTime()),
            ],
        ]);
    }
}
