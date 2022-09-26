<?php

namespace App\Controller;

use App\Entity\Drink;
use App\Form\DrinkType;
use App\Repository\DrinkRepository;
use App\Repository\SoberRepository;
use App\Service\SoberService;
use App\Service\UserStatsService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DrinkController extends AbstractController
{
    public function __construct(
        private  EntityManagerInterface $entityManager,
        private DrinkRepository $drinkRepository,
        private SessionInterface $session
    ) {
    }

    /**
     * @Route("/consommations/voir", name="drink_calendar")
     */
    public function getCalendar(
        SoberRepository $soberRepository,
        UserStatsService $userStatsService
    ) {
        $user      = $this->getUser();
        $drinks    = $this->drinkRepository->findByUser($user);
        $lastDrink = $this->drinkRepository->findLastDrink($user);
        $sobers    = $soberRepository->findByUser($user);
        $lastSoberPeriod = $userStatsService->getLastSoberPeriod($user);

        $totalMoneySaved = 0;
        foreach ($user->getMoney() as $value) {
            $totalMoneySaved += $value->getAmount();
        }

        if ($lastDrink->getDate() < new DateTime('today')) {
            $lastDrink = false;
        }

        return $this->render('drink/calendar.html.twig', [
            'drinks'          => $drinks,
            'sobers'          => $sobers,
            'lastDrink'       => $lastDrink,
            'moneySaved'      => $totalMoneySaved,
            'lastSoberPeriod' => $lastSoberPeriod
        ]);
    }

    /**
     * @Route("/consommation/ajouter/une/conso", name="drink_add_one_more")
     */
    public function addOneMoreDrink()
    {
        $user = $this->getUser();
        $lastDrink = $this->drinkRepository->findLastDrink($user);
        $lastDrinkQuantity = $lastDrink->getQuantity();
        $lastDrinkCost = $lastDrink->getCost();

        $lastDrink->setQuantity($lastDrinkQuantity += 1);

        if (!false == $this->session->get('lastDrinkCost') || 0 == $this->session->get('lastDrinkCost')) {
            $lastDrink->setCost($lastDrinkCost += $this->session->get('lastDrinkCost'));
        } else {
            $this->addFlash('danger', 'Votre dernier enregistrement semble dater un peu ou vous avez ajouté plusieurs unités à la fois, veuillez mettre à jour votre consommation manuellement');

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
        SoberService $soberService
    ) {
        $form = $this->createForm(DrinkType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $date = $form->get('date')->getData();

            // Check for existing drink (same alcool + same date)
            $existingDrink = $this->drinkRepository->findExistingDrink($user, $date, $form->get('alcool')->getData());

            if (!$existingDrink) {
                $drink = new Drink();
                $drink->setCost($form->get('cost')->getData());
                $drink->setQuantity($form->get('quantity')->getData());
            } else {
                $drink = $existingDrink[0];
                $drink->setCost($form->get('cost')->getData() + $existingDrink[0]->getCost());
                $drink->setQuantity($form->get('quantity')->getData() + $existingDrink[0]->getQuantity());
            }

            $drink->setUser($user);
            $drink->setAlcool($form->get('alcool')->getData());
            $drink->setDate($date);

            // Remove auto sober day if option is activated
            $soberService->removeAutoSoberDay($user);

            // Checking if soberDay exists the same day of the drink
            $soberDay = $soberService->checkExistingSober($user, $date);

            // If exist, remove soberDay
            if ($soberDay) {
                $soberService->removeSoberDay($soberDay);
            }

            $this->entityManager->persist($drink);
            $this->entityManager->flush();

            // If drink quantity = 1, registering in session for +1 option
            if (1 === $drink->getQuantity()) {
                $this->session->set('lastDrinkCost', $drink->getCost());
                if (null == $drink->getCost()) {
                    $drink->setCost(0);
                    $this->session->set('LastDrinkCost', 0);
                }
            } else {
                $this->session->clear();
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

            // Checking if soberDay exists the same day of the drink
            $soberDay = $soberService->checkExistingSober($this->getUser(), $form->get('date')->getData());

            // If exist, remove soberDay
            if ($soberDay) {
                $soberService->removeSoberDay($soberDay);
            }

            // Update session for +1drink button
            if (1 === $drink->getQuantity()) {
                $this->session->set('lastDrinkCost', $drink->getCost());
                if (null == $drink->getCost()) {
                    $drink->setCost(0);
                    $this->session->set('LastDrinkCost', 0);
                }
            } else {
                $this->session->clear();
            }

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
