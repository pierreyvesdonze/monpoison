<?php

namespace App\Controller;

use App\Entity\Money;
use App\Form\MoneyType;
use App\Repository\MoneyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/money')]
class MoneyController extends AbstractController
{
    #[Route('/', name: 'money_index', methods: ['GET'])]
    public function index(MoneyRepository $moneyRepository): Response
    {
        return $this->render('money/index.html.twig', [
            'money' => $moneyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'money_add', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $money = new Money();
        $form = $this->createForm(MoneyType::class, $money);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $money->setUser($this->getUser());
            $entityManager->persist($money);
            $entityManager->flush();

            $this->addFlash('success', "Nouvelle économie d'argent enregistrée !");

            return $this->redirectToRoute('drink_calendar', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('money/new.html.twig', [
            'money' => $money,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'money_show', methods: ['GET'])]
    public function show(Money $money): Response
    {
        return $this->render('money/show.html.twig', [
            'money' => $money,
        ]);
    }

    #[Route('/{id}/edit', name: 'money_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Money $money, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MoneyType::class, $money);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('money_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('money/edit.html.twig', [
            'money' => $money,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'money_delete', methods: ['POST'])]
    public function delete(Request $request, Money $money, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$money->getId(), $request->request->get('_token'))) {
            $entityManager->remove($money);
            $entityManager->flush();
        }

        return $this->redirectToRoute('money_index', [], Response::HTTP_SEE_OTHER);
    }
}
