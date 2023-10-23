<?php

namespace App\Controller;

use App\Entity\Testimonials;
use App\Form\TestimonialsType;
use App\Repository\TestimonialsRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("temoignage")
 */
class TestimonialsController extends AbstractController
{
    #[Route('s', name: 'testimonials', methods: ['GET'])]
    public function index(
        TestimonialsRepository $testimonialsRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $data = $testimonialsRepository->findAllByDescId();
        $testimonials = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('testimonials/testimonials.html.twig', [
            'testimonials' => $testimonials
        ]);
    }

    #[Route('/ajouter', name: 'testimonials_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MailService $mailService
    ): Response {
        $testimonial = new Testimonials();
        $form = $this->createForm(TestimonialsType::class, $testimonial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($testimonial);
            $entityManager->flush();

            $mailService->sendTestimonialMail($testimonial->getContent(), $this->getUser());

            $this->addFlash('success', 'Votre témoignage a bien été enregistré !');

            return $this->redirectToRoute('testimonials', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('testimonials/new.html.twig', [
            'testimonial' => $testimonial,
            'form' => $form,
        ]);
    }

    #[Route('/voir/{id}', name: 'testimonials_show', methods: ['GET'])]
    public function show(
        Testimonials $testimonial
    ): Response {
        return $this->render('testimonials/show.html.twig', [
            'testimonial' => $testimonial,
        ]);
    }

    #[Route('/editer/{id}', name: 'testimonial_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Testimonials $testimonial, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(TestimonialsType::class, $testimonial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('testimonials_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('testimonials/edit.html.twig', [
            'testimonial' => $testimonial,
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name: 'delete_testimonial', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(
        Testimonials $testimonial,
        EntityManagerInterface $entityManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager->remove($testimonial);
        $entityManager->flush();

        $this->addFlash('success', 'Témoignage supprimé !');

        return $this->redirectToRoute('testimonials', [], Response::HTTP_SEE_OTHER);
    }
}
