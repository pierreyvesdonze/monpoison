<?php

namespace App\Controller;

use App\Repository\DrinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

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

        if(null !== $totalBeer && null !== $totalWine && null !== $totalSpiritus) {
            $xBeer     = ((int)$totalBeer * 100) / $totalDrink;
            $xWine     = ((int)$totalWine * 100) / $totalDrink;
            $xSpiritus = ((int)$totalSpiritus * 100) / $totalDrink;
        } else {
            $xBeer = $xWine = $xSpiritus = 0;
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
