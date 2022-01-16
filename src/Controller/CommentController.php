<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class CommentController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/', name: 'comment_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    #[Route('/ajouter/{id}', name: 'comment_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        Post $post,
        MailService $mailService
    ): Response {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            $comment->setDate(new \DateTime('now'));
            $this->em->persist($comment);
            $this->em->flush();

            // Send comment to admin by email
            $mailService->sendCommentMail($comment, $this->getUser());

            return $this->redirectToRoute('post_show', [
                'id' => $post->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'post' => $post
        ]);
    }

    #[Route('/voir/{id}', name: 'comment_show', methods: ['GET'])]
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('/{id}/editer', name: 'comment_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Comment $comment
    ): Response {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('post_show', [
                'id' => $comment->getPost()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name: 'comment_delete', methods: ['POST', 'GET'])]
    public function delete(
        Request $request,
        Comment $comment
    ): Response {

        $this->em->remove($comment);
        $this->em->flush();

        return $this->redirectToRoute('post_show', [
            'id' => $comment->getPost()->getId()
        ], Response::HTTP_SEE_OTHER);
    }
}
