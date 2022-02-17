<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\SubscriberRepository;
use App\Service\MailService;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class PostController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    #[Route('s', name: 'post_index', methods: ['GET'])]
    public function index(
        PostRepository $postRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        $data = $postRepository->findBy([], ['date' => 'desc']);
        // $posts = $paginator->paginate(
        //     $data,
        //     $request->query->getInt('page', 1),
        //     6
        // );
        $posts = $postRepository->findAllByDesc();
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/status", name="post_status", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function postStatus(PostRepository $postRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $posts = $postRepository->findBy([], ['date' => 'desc']);
        return $this->render('post/status.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/publier/status/{type}/{id}", name="edit_post_status_publish", methods={"GET","POST"})
     * @Route("/retirer/status/{type}{id}", name="edit_post_status_remove", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function publishPostStatus(
        Post $post,
        $type,
        SubscriberRepository $subscriberRepository,
        MailService $mailService
    ) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ('publish' === $type) {
            $post->setIsPublished(1);
            $this->addFlash('success', 'Article publié !');

            // Notify subscribers
            $subscribers = $subscriberRepository->findAll();

            foreach ($subscribers as $recipient) {
                if ("production" === $this->getParameter('app.env')) {
                    $mailService->sendSubscribersNewPost($recipient->getEmail(), $post);
                }
            }
        } elseif ('remove' === $type) {
            $post->setIsPublished(0);
            $this->addFlash('success', 'Article dépublié !');
        }
        $this->em->flush();

        return $this->redirectToRoute('post_status', []);
    }

    /**
     * @Route("/retirer/status/{id}", name="edit_post_status_remove", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function removePostStatus(Post $post)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $post->setIsPublished(0);
        $this->em->flush();
        $this->addFlash('success', 'Article dépublié !');

        return $this->redirectToRoute('post_status', []);
    }

    /**
     * @Route("/nouveau", name="post_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/voir/{id}/{slug}', name: 'post_show', methods: ['GET'])]
    public function show(
        Post $post,
        CommentRepository $commentRepository
    ): Response {
        $comments = $commentRepository->findCommentsByPost($post);

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/{id}/editer", name="post_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(
        Request $request,
        Post $post
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $isPublished = $form->get('isPublished')->getData();
            if ($isPublished === 0) {
                $post->setIsPublished(1);
            } else {
                $post->setIsPublished(0);
            }
            $this->em->flush();

            return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="post_delete",  methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(
        Request $request,
        Post $post
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $this->em->remove($post);
            $this->em->flush();
        }

        return $this->redirectToRoute('post_index', [], Response::HTTP_SEE_OTHER);
    }
}
