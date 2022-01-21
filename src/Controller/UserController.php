<?php

namespace App\Controller;

use App\Repository\ArgumentUserRepository;
use App\Repository\DrinkRepository;
use App\Repository\SoberRepository;
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
    public function index(
        DrinkRepository $drinkRepository,
        SoberRepository $soberRepository,
        ArgumentUserRepository $argRepo,
    ): Response {
        $user = $this->getUser();
        $emConfig = $this->em->getConfiguration();
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');

        $lastWeekDrinks    = $drinkRepository->findLastWeekDrinks($user);
        $lastWeekCost      = $drinkRepository->findLastWeekCost($user);
        $drinks            = $drinkRepository->findByUser($user);
        $totalBeer         = $drinkRepository->findTotalBeer($user)[1];
        $totalWine         = $drinkRepository->findTotalWine($user)[1];
        $totalSpiritus     = $drinkRepository->findTotalSpiritus($user)[1];
        $advantagesUser    = $argRepo->findAdvantagesByUser($user);
        $inconvenientsUser = $argRepo->findInconvenientsByUser($user);

        $mondayDrinks    = (int)$drinkRepository->findByDay($user, 'Monday')[0][1];
        $tuesdayDrinks   = (int)$drinkRepository->findByDay($user, 'Tuesday')[0][1];
        $wednesdayDrinks = (int)$drinkRepository->findByDay($user, 'Wednesday')[0][1];
        $thursdayDrinks  = (int)$drinkRepository->findByDay($user, 'Thursday')[0][1];
        $fridayDrinks    = (int)$drinkRepository->findByDay($user, 'Friday')[0][1];
        $saturdayDrinks  = (int)$drinkRepository->findByDay($user, 'Saturday')[0][1];
        $sundayDrinks    = (int)$drinkRepository->findByDay($user, 'Sunday')[0][1];

        // Sobers Days
        $sobers          = count($soberRepository->findByUser($user));

        // Get dates sorted by ASC to calculate longest period of sobriety
        $sobersDates = $soberRepository->findDatesByUser($user);

        $period = 0;
        $periodMax = 0;
        $previousDay = null;
        $calculPreviousDay = null;
   
        foreach($sobersDates as $soberDate) {
            $calculPreviousDay = clone $soberDate['date'];
            $calculPreviousDay->modify('-1 day');

            if($calculPreviousDay == $previousDay) {
                $period ++;
            } else {
                if ($period > $periodMax) {
                    $periodMax = $period;
                }
                $period = 1;
            }
            $previousDay = $soberDate['date'];
        }

        if ($period > $periodMax) {
            $periodMax = $period;
        }

        // Total drinks
        $total = (int)$totalBeer + (int)$totalWine + (int)$totalSpiritus + (int)$sobers;
 
        // ratio of drinks & sobers
        if (0 !== $total) {
            if (null !== $sobers) {
                $xSober    = ((int)$sobers * 100) / $total;
            } else {
                $xSober = 0;
            }
            if (null !== $totalBeer) {
                $xBeer     = ((int)$totalBeer * 100) / $total;
            } else {
                $xBeer = 0;
            }
            if (null !== $totalWine) {
                $xWine     = ((int)$totalWine * 100) / $total;
            } else {
                $xWine = 0;
            }
            if (null !== $totalSpiritus) {
                $xSpiritus = ((int)$totalSpiritus * 100) / $total;
            } else {
                $xSpiritus = 0;
            }
        } else {
            $xSober = $xBeer = $xWine = $xSpiritus = 0;
        }

        // ratio of argumentsUsers
        $totalArgUsers = count($advantagesUser) + count($inconvenientsUser);

        if (0 !== $totalArgUsers) {
            if (null !== count($advantagesUser)) {
                $xAdvantageUser = count($advantagesUser) * 100 / $totalArgUsers;
            } else {
                $xAdvantageUser = 0;
            }
            if (null !== count($inconvenientsUser)) {
                $xInconvenientUser = count($inconvenientsUser) * 100 / $totalArgUsers;
            } else {
                $xInconvenientUser = 0;
            }
        } else {
            $xAdvantageUser = $xInconvenientUser = 0;
        }

        return $this->render('user/user.html.twig', [
            'user'           => $user,
            'drinks'         => $drinks,
            'sobers'         => $sobers,
            'lastWeekDrinks' => $lastWeekDrinks,
            'lastWeekCost'   => $lastWeekCost,
            'xBeer'          => $xBeer,
            'xWine'          => $xWine,
            'xSpiritus'      => $xSpiritus,
            'xSober'         => $xSober,
            'xAdvantages'    => $xAdvantageUser,
            'xInconvenients' => $xInconvenientUser,
            'mondayDrinks'   => $mondayDrinks,
            'tuesdayDrinks'  => $tuesdayDrinks,
            'wednesdayDrinks'=> $wednesdayDrinks,
            'thursdayDrinks' => $thursdayDrinks,
            'fridayDrinks'   => $fridayDrinks,
            'saturdayDrinks' => $saturdayDrinks,
            'sundayDrinks'   => $sundayDrinks
        ]);
    }
}
