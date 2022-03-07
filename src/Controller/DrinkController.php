<?php

namespace App\Controller;

use App\Entity\Drink;
use App\Form\DrinkType;
use App\Repository\DrinkRepository;
use App\Repository\SoberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DrinkController extends AbstractController
{
    public function __construct(
        private  EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @Route("/consommations/voir", name="drink_calendar")
     */
    public function getCalendar(
        DrinkRepository $drinkRepository,
        SoberRepository $soberRepository
    ) {
        $drinks = $drinkRepository->findByUser($this->getUser());
        $sobers = $soberRepository->findByUser($this->getUser());

        return $this->render('drink/calendar.html.twig', [
            'drinks' => $drinks,
            'sobers' => $sobers
        ]);
    }

    /**
     * @Route("/consommation/ajouter", name="drink_add")
     */
    public function addDrink(
        Request $request,
        DrinkRepository $drinkRepository
    ) {
        $form = $this->createForm(DrinkType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Check for existing drink (same alcool + same date)
            $existingDrink = $drinkRepository->findExistingDrink($this->getUser(), $form->get('date')->getData(), $form->get('alcool')->getData());

            if (!$existingDrink) {
                $drink = new Drink();
                $drink->setCost($form->get('cost')->getData());
                $drink->setQuantity($form->get('quantity')->getData());
            } else {
                $drink = $existingDrink[0];
                $drink->setCost($form->get('cost')->getData() + $existingDrink[0]->getCost());
                $drink->setQuantity($form->get('quantity')->getData() + $existingDrink[0]->getQuantity());
            }

            $drink->setUser($this->getUser());
            $drink->setAlcool($form->get('alcool')->getData());
            $drink->setDate($form->get('date')->getData());

            $this->entityManager->persist($drink);
            $this->entityManager->flush();

            $this->addFlash('success', 'Nouvelle consommation enregistrée !');

            return $this->redirectToRoute('drink_calendar');
        }

        return $this->render('drink/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/consommation/editer/{id}", name="drink_update")
     */
    public function drinkUpdate(
        Drink $drink,
        Request $request
    ) {
        $form = $this->createForm(DrinkType::class, $drink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $drink->setAlcool($form->get('alcool')->getData());
            $drink->setDate($form->get('date')->getData());
            $drink->setQuantity($form->get('quantity')->getData());
            $drink->setCost($form->get('cost')->getData());

            $this->entityManager->flush();
            $this->addFlash('success', 'Consommation mise à jour !');

            return $this->redirectToRoute('drink_calendar');
        }

        return $this->render('drink/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/consommation/supprimer/{id}", name="drink_delete")
     */
    public function drinkDelete(Drink $drink)
    {
        $this->entityManager->remove($drink);
        $this->entityManager->flush();

        $this->addFlash('success', 'La consommation a été supprimée !');

        return $this->redirectToRoute('drink_calendar');
    }
}
