<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class CommentController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/comments', name: 'comment_index', methods: ['GET'])]
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

            $mailService->sendCommentMail(
                $comment,
                $this->getUser());

            $this->addFlash('success', 'Votre commentaire a bien été ajouté !');

            return $this->redirectToRoute('post_show', [
                'id' => $post->getId(),
                'slug' => $post->getSlug()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form'    => $form,
            'post'    => $post
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

            $this->addFlash('success', 'Votre commentaire a bien été modifié !');

            return $this->redirectToRoute('post_show', [
                'id' => $comment->getPost()->getId(),
                'slug' => $comment->getPost()->getSlug()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name: 'comment_delete', methods: ['POST', 'GET'])]
    public function delete(
        Comment $comment
    ): Response {
        $this->em->remove($comment);
        $this->em->flush();

        $this->addFlash('success', 'Le commentaire a bien été supprimé !');

        return $this->redirectToRoute('post_show', [
            'id' => $comment->getPost()->getId(),
            'slug' => $comment->getPost()->getSlug()
        ], Response::HTTP_SEE_OTHER);
    }
}
