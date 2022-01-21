<?php

namespace App\Controller;

use App\Service\UserStatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @Route("/user/profile", name="user_account")
     */
    public function index(UserStatsService $userStatsService): Response {
        $user = $this->getUser();

        // Get total of drinks
        $statsArray = $userStatsService->getDrinksStats($user);

        // Get total of Sobers Days
        $sobers = $userStatsService->getSobersdays($user);

        // Get dates sorted by ASC to calculate longest period of sobriety
        $periodMax = $userStatsService->getMaxSobrietyPeriod($user);

        // Get 7 days last drinks
        $lastWeekDrinks = $userStatsService->getLastWeekDrinks($user);

        // Get 7 days cost
        $lastWeekCost = $userStatsService->getLastWeekCost($user);

        // Get all days of drinking day by day
        $weekDrinks = $userStatsService->getDrinksByDay($user);

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
}
