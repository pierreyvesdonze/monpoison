<?php

namespace App\Controller;

use App\Entity\Testimonials;
use App\Form\TestimonialsType;
use App\Repository\TestimonialsRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("temoignage")
 */
class TestimonialsController extends AbstractController
{
    /**
     * @Route("s", name="testimonials", methods="GET")
     */
    public function index(
        TestimonialsRepository $testimonialsRepository,
        PaginatorInterface $paginator,
        Request $request
        ): Response
    {
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

            $this->addFlash('success', 'Votre témoignage a bien été enregistré !');

            // Send notification to contact@monpoison.fr
            if ("production" === $this->getParameter('app.env')) {
                $mailService->sendTestimonialMail(
                    $form->get('content')->getData(),
                    $form->get('pseudo')->getData()
                );
            }
            return $this->redirectToRoute('testimonials', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('testimonials/new.html.twig', [
            'testimonial' => $testimonial,
            'form' => $form,
        ]);
    }

    #[Route('/voir/{id}', name: 'testimonials_show', methods: ['GET'])]
    public function show(
        Testimonials $testimonial,
        PaginatorInterface $paginatorInterface
        ): Response
    {
        return $this->render('testimonials/show.html.twig', [
            'testimonial' => $testimonial,
        ]);
    }

    #[Route('/{id}/editer', name: 'testimonials_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Testimonials $testimonial, EntityManagerInterface $entityManager): Response
    {
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

    #[Route('/{id}/supprimer', name: 'testimonials_delete', methods: ['POST'])]
    public function delete(Request $request, Testimonials $testimonial, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$testimonial->getId(), $request->request->get('_token'))) {
            $entityManager->remove($testimonial);
            $entityManager->flush();
        }
        return $this->redirectToRoute('testimonials_index', [], Response::HTTP_SEE_OTHER);
    }
}
