<?php

namespace App\Controller;

use App\Entity\ArgumentUser;
use App\Entity\Goal;
use App\Form\ArgumentType;
use App\Form\GoalType;
use App\Repository\ArgumentUserRepository;
use App\Repository\GoalRepository;
use App\Service\UserStatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @Route("/user/profile", name="user_account")
     */
    public function index(UserStatsService $userStatsService): Response
    {
        $user = $this->getUser();

        // Get total of drinks
        $statsArray     = $userStatsService->getDrinksStats($user);

        // Get total of Sobers Days
        $sobers         = $userStatsService->getSobersdays($user);

        // Get dates sorted by ASC to calculate longest period of sobriety
        $periodMax      = $userStatsService->getMaxSobrietyPeriod($user);

        // Get 7 days last drinks
        $lastWeekDrinks = $userStatsService->getLastWeekDrinks($user);

        // Get 7 days cost
        $lastWeekCost   = $userStatsService->getLastWeekCost($user);

        // Get all days of drinking day by day
        $weekDrinks     = $userStatsService->getDrinksByDay($user);

        // Get ratio of arguments & inconvenient 
        $ratioAdvInconv = $userStatsService->getRatioAdvantageInconvenient($user);

        return $this->render('user/user.html.twig', [
            'user'           => $user,
            'statsArray'     => $statsArray,
            'sobers'         => $sobers,
            'lastWeekDrinks' => $lastWeekDrinks,
            'lastWeekCost'   => $lastWeekCost,
            'ratioAdvInconv' => $ratioAdvInconv,
            'weekDrinks'     => $weekDrinks,
            'periodMax'      => $periodMax
        ]);
    }

    /**
     * @Route("/alcool/avantages/inconvenients", name="alcool_arguments")
     */
    public function alcoolArguments(
        ArgumentUserRepository $arguRepo
    ): Response {
        $user = $this->getUser();

        $arguments = $arguRepo->findAllByUser($user);

        return $this->render('alcool/arguments.html.twig', [
            'arguments' => $arguments
        ]);
    }

    /**
     * @Route("/alcool/avantages/inconvenients/ajouter", name="alcool_arguments_add")
     */
    public function addArgument(Request $request)
    {

        $form = $this->createForm(ArgumentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newArgument = new ArgumentUser;
            $newArgument->setType($form->get('type')->getData());
            $newArgument->setContent($form->get('content')->getData());
            $newArgument->setUser($this->getUser());

            $this->em->persist($newArgument);
            $this->em->flush();

            $this->addFlash('success', "Argument ajouté");

            return $this->redirectToRoute('alcool_arguments');
        }
        return $this->render('alcool/add.arguments.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/alcool/avantages/inconvenients/retirer/{id}", name="alcool_arguments_remove")
     */
    public function removeArgument(ArgumentUser $argument)
    {
        if ($this->getUser() == $argument->getUser()) {

            $this->em->remove($argument);
            $this->em->flush();

            $this->addFlash('success', 'Retiré !');
        }
        return $this->redirectToRoute('alcool_arguments');
    }

    /**
     * @Route("/objectifs", name="goals")
     */
    public function goals(GoalRepository $goalRepository)
    {
        $goals = $goalRepository->findByUser($this->getUser());

        return $this->render('goal/goals.html.twig', [
            'goals' => $goals
        ]);
    }

    /**
     * @Route("/ajouter/objectif", name="add_goal")
     */
    public function addGoal(Request $request)
    {
        $form = $this->createForm(GoalType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $goal = new Goal;
            $goal->setContent($form->get('content')->getData());
            $goal->setIsAchieved($form->get('isAchieved')->getData());
            $goal->setUser($this->getUser());

            $this->em->persist($goal);
            $this->em->flush();

            $this->addFlash('success', 'Nouvel objectif ajouté !');

            return $this->redirectToRoute('goals');
        }
        return $this->render('goal/add.goal.html.twig', [
            'form' => $form->createView()
        ]);      
    }

    /**
     * @Route("/supprimer/objectif/{id}", name="remove_goal")
     */
    public function removeGoal(Goal $goal)
    {
        if ($this->getUser() === $goal->getUser()) {
            $this->em->remove($goal);
            $this->em->flush();
            $this->addFlash('success', 'Objectif supprimé !');
        }
        return $this->redirectToRoute('goals');
    }

    /**
     * @Route("/changer/validation/objectif/{goalId}", name="set-achievement", options={"expose"=true}, methods="POST")
     */
    public function setAchievement($goalId, GoalRepository $goalRepository)
    {
        $goal = $goalRepository->findOneById($goalId);

        $goal->getIsAchieved(0) ? $goal->setIsAchieved(1) : $goal->setIsAchieved(0);

        $goal->getIsAchieved(1) ? $goal->setIsAchieved(0) : $goal->setIsAchieved(1);

        $this->em->flush();

        return new JsonResponse($goalId);
    }

}
