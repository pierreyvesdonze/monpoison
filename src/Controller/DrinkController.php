<?php

namespace App\Controller;

use App\Entity\Drink;
use App\Form\DrinkType;
use App\Repository\DrinkRepository;
use App\Repository\SoberRepository;
use App\Service\SoberService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DrinkController extends AbstractController
{
    public function __construct(
        private  EntityManagerInterface $entityManager,
        private DrinkRepository $drinkRepository
    ) {
    }

    /**
     * @Route("/consommations/voir", name="drink_calendar")
     */
    public function getCalendar(
        SoberRepository $soberRepository
    ) {
        $user      = $this->getUser();
        $drinks    = $this->drinkRepository->findByUser($user);
        $lastDrink = $this->drinkRepository->findLastDrink($user);
        $sobers    = $soberRepository->findByUser($user);

        return $this->render('drink/calendar.html.twig', [
            'drinks'    => $drinks,
            'sobers'    => $sobers,
            'lastDrink' => $lastDrink
        ]);
    }

    /**
     * @Route("/consommation/ajouter/une/conso", name="drink_add_one_more")
     */
    public function addOneMoreDrink(SessionInterface $session)
    {
        $user = $this->getUser();
        $lastDrink = $this->drinkRepository->findLastDrink($user);
        $lastDrinkQuantity = $lastDrink->getQuantity();
        $lastDrinkCost = $lastDrink->getCost();

        $lastDrink->setQuantity($lastDrinkQuantity += 1);

        if (!null == $session->get('lastDrinkCost')) {
            $lastDrink->setCost($lastDrinkCost += $session->get('lastDrinkCost'));
        } else {
            $this->addFlash('danger', 'Votre dernier enregistrement semble dater un peu ou vous avez ajouter plusieurs unités à la fois, veuillez mettre à jour votre consommation manuellement');

            return $this->redirectToRoute('drink_calendar');
        }

        $this->entityManager->flush();

        $this->addFlash('success', '+ 1 conso ajoutée');

        return $this->redirectToRoute('drink_calendar');
    }

    /**
     * @Route("/consommation/ajouter", name="drink_add")
     */
    public function addDrink(
        Request $request,
        SoberService $soberService,
        SessionInterface $session
    ) {

        $form = $this->createForm(DrinkType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Check for existing drink (same alcool + same date)
            $existingDrink = $this->drinkRepository->findExistingDrink($this->getUser(), $form->get('date')->getData(), $form->get('alcool')->getData());

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

            // Remove auto sober day if option is activated
            $soberService->removeAutoSoberDay($this->getUser());

            $this->entityManager->persist($drink);
            $this->entityManager->flush();

            // If drink quantity = 1, registering in session for +1 option
            if (1 === $drink->getQuantity()) {
                $session->set('lastDrinkCost', $drink->getCost());
            } else {
                $session->clear();
            }

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
        Request $request,
        SoberService $soberService
    ) {
        $form = $this->createForm(DrinkType::class, $drink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $drink->setAlcool($form->get('alcool')->getData());
            $drink->setDate($form->get('date')->getData());
            $drink->setQuantity($form->get('quantity')->getData());
            $drink->setCost($form->get('cost')->getData());

            // Remove auto sober day if option is activated
            $soberService->removeAutoSoberDay($this->getUser());

            $this->entityManager->flush();
            $this->addFlash('success', 'Consommation mise à jour !');

            return $this->redirectToRoute('drink_calendar');
        }

        return $this->render('drink/add.html.twig', [
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
