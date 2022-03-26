<?php

namespace App\Service;

use App\Entity\Sober;
use App\Repository\DrinkRepository;
use App\Repository\SoberRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class SoberService
{
    public function __construct(
        private SoberRepository $soberRepository,
        private DrinkRepository $drinkRepository,
        private EntityManagerInterface $em
    ) {
    }

    public function addAutoSoberDay($user)
    {
        $soberDay = $this->soberRepository->findByUserAndByDate($user, new DateTime('today'));
        $drinkDay = $this->drinkRepository->findByUserAndByDate($user, new DateTime('today'))[0][1];

        if (true == $user->getAutoSober() && null == $drinkDay && null == $soberDay) {

            $newSoberDay = new Sober;
            $newSoberDay->setUser($user);
            $newSoberDay->setDate(new DateTime('now'));

            $this->em->persist($newSoberDay);
            $this->em->flush();

            return $newSoberDay;
        }

        return null;
    }

    public function removeAutoSoberDay($user) {
        $soberDay = $this->soberRepository->findByUserAndByDate($user, new DateTime('today'))[0];
        $drinkDay = $this->drinkRepository->findByUserAndByDate($user, new DateTime('today'))[0][1];

        if (true == $user->getAutoSober() && false == $drinkDay && true == $soberDay) {

            $this->em->remove($soberDay);
            $this->em->flush();

            return $soberDay;
        }

        return null;
    }
}
