<?php

namespace App\Controller;

use App\Repository\DrinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{


    public function __construct(
       private EntityManagerInterface $entityManager
    ) {}

    /**
     * @Route("/user", name="user_account")
     */
    public function index(DrinkRepository $drinkRepository): Response
    {
        $user = $this->getUser();

        $lastWeekDrinks = $drinkRepository->findLastWeekDrinks($user);
        $lastWeekCost   = $drinkRepository->findLastWeekCost($user);
        $drinks         = $drinkRepository->findByUser($user);
        $totalBeer      = $drinkRepository->findTotalBeer($user)[1];
        $totalWine      = $drinkRepository->findTotalWine($user)[1];
        $totalSpiritus  = $drinkRepository->findTotalSpiritus($user)[1];

        $totalDrink = (int)$totalBeer + (int)$totalWine + (int)$totalSpiritus;

        if (null !== $totalBeer) {
            $xBeer     = ((int)$totalBeer * 100) / $totalDrink;
        } else {
            $xBeer = 0;
        }
        if (null !== $totalWine) {
            $xWine     = ((int)$totalWine * 100) / $totalDrink;
        } else {
            $xWine = 0;
        }
        if (null !== $totalSpiritus) {
            $xSpiritus = ((int)$totalSpiritus * 100) / $totalDrink;
        } else {
            $xSpiritus = 0;
        }

        return $this->render('user/user.html.twig', [
            'user'           => $user,
            'drinks'         => $drinks,
            'lastWeekDrinks' => $lastWeekDrinks,
            'lastWeekCost'   => $lastWeekCost,
            'xBeer'          => $xBeer,
            'xWine'          => $xWine,
            'xSpiritus'      => $xSpiritus
        ]);
    }
}
