<?php

namespace App\Controller;

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
        SoberRepository $soberRepository
    ): Response {
        $user = $this->getUser();
        $emConfig = $this->em->getConfiguration();
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');

        $lastWeekDrinks  = $drinkRepository->findLastWeekDrinks($user);
        $lastWeekCost    = $drinkRepository->findLastWeekCost($user);
        $drinks          = $drinkRepository->findByUser($user);
        $totalBeer       = $drinkRepository->findTotalBeer($user)[1];
        $totalWine       = $drinkRepository->findTotalWine($user)[1];
        $totalSpiritus   = $drinkRepository->findTotalSpiritus($user)[1];

        $mondayDrinks    = (int)$drinkRepository->findByDay($user, 'Monday')[0][1];
        $tuesdayDrinks   = (int)$drinkRepository->findByDay($user, 'Tuesday')[0][1];
        $wednesdayDrinks = (int)$drinkRepository->findByDay($user, 'Wednesday')[0][1];
        $thursdayDrinks  = (int)$drinkRepository->findByDay($user, 'Thursday')[0][1];
        $fridayDrinks    = (int)$drinkRepository->findByDay($user, 'Friday')[0][1];
        $saturdayDrinks  = (int)$drinkRepository->findByDay($user, 'Saturday')[0][1];
        $sundayDrinks    = (int)$drinkRepository->findByDay($user, 'Sunday')[0][1];

        dump($mondayDrinks);
     

        $sobers         = count($soberRepository->findByUser($user));

        $total = (int)$totalBeer + (int)$totalWine + (int)$totalSpiritus + (int)$sobers;
 
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
            'mondayDrinks'   => $mondayDrinks,
            'tuesdayDrinks'  => $tuesdayDrinks,
            'wednesdayDrinks'=> $wednesdayDrinks,
            'thursdayDrinks' => $thursdayDrinks,
            'fridayDrinks'   => $fridayDrinks,
            'saturdayDrinks' => $saturdayDrinks,
            'sundayDrinks'  => $sundayDrinks
        ]);
    }
}
