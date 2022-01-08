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
        //$lastWeekDrink = $drinkRepository->findLastWeek($user);  

        $drinks = $drinkRepository->findByUser($user);

         // $qb = $this->entityManager->createQuery('SELECT count(d) FROM drink where d.user = $user and d.date > :date');
        // $qb->setParameter('date', new \DateTime('-1 week'));

        return $this->render('user/user.html.twig', [
            'user' => $user,
            'drinks' => $drinks
        ]);
    }
}
