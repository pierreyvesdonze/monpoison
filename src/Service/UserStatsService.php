<?php

namespace App\Service;

use App\Repository\SoberRepository;
use App\Repository\DrinkRepository;
use App\Repository\ArgumentUserRepository;

class UserStatsService
{
    public function __construct(
        private SoberRepository $soberRepository,
        private DrinkRepository $drinkRepository,
        private ArgumentUserRepository $argRepo
        )
    {}

    // Get dates sorted by ASC to calculate longest period of sobriety
    public function getMaxSobrietyPeriod($user)
    {
        $sobersDates = $this->soberRepository->findDatesByUser($user);

        $period = 0;
        $periodMax = 0;
        $previousDay = null;
        $calculPreviousDay = null;

        foreach ($sobersDates as $soberDate) {
            $calculPreviousDay = clone $soberDate['date'];
            $calculPreviousDay->modify('-1 day');

            if ($calculPreviousDay == $previousDay) {
                $period++;
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

        return $periodMax;
    }

    // Get total of Sobers Days
    public function getSobersdays($user)
    {
        return count($this->soberRepository->findByUser($user));
    }

    // Get Total of drinks with ratios
    public function getDrinksStats($user)
    {
        $drinks        = $this->drinkRepository->findByUser($user);
        $totalBeer     = $this->drinkRepository->findTotalBeer($user)[1];
        $totalWine     = $this->drinkRepository->findTotalWine($user)[1];
        $totalSpiritus = $this->drinkRepository->findTotalSpiritus($user)[1];

        // Final object
        $statsArray = [];

        // Total sobers
        $sobers = $this->getSobersdays($user);

        // Total drinks
        $totalDrinks = (int)$totalBeer + (int)$totalWine + (int)$totalSpiritus + (int)$sobers;

        // ratio of drinks & sobers
        if (0 !== $totalDrinks) {
            if (null !== $sobers) {
                $xSober    = ((int)$sobers * 100) / $totalDrinks;
            } else {
                $xSober = 0;
            }
            if (null !== $totalBeer) {
                $xBeer     = ((int)$totalBeer * 100) / $totalDrinks;
            } else {
                $xBeer = 0;
            }
            if (null !== $totalWine) {
                $xWine     = ((int)$totalWine * 100) / $totalDrinks;
            } else {
                $xWine = 0;
            }
            if (null !== $totalSpiritus) {
                $xSpiritus = ((int)$totalSpiritus * 100) / $totalDrinks;
            } else {
                $xSpiritus = 0;
            }
        } else {
            $xSober = $xBeer = $xWine = $xSpiritus = 0;
        }

        $statsArray['drinks']     = $drinks;
        $statsArray['xSober']     = $xSober;
        $statsArray['xBeer']      = $xBeer;
        $statsArray['xWine']      = $xWine;
        $statsArray ['xSpiritus'] = $xSpiritus;

       return $statsArray;
    }

    // Get 7 days last drinks
    public function getLastWeekDrinks($user)
    {
       return $this->drinkRepository->findLastWeekDrinks($user);
    }

    // Get 7 days cost
    public function getLastWeekCost($user)
    {
        return $this->drinkRepository->findLastWeekCost($user);
    }

    // Get all days of drinking day by day
    public function getDrinksByDay($user)
    {
        $weekDrinks = [];

        $weekDrinks['monday']    = (int)$this->drinkRepository->findByDay($user, 'Monday')[0][1];
        $weekDrinks['tuesday']   = (int)$this->drinkRepository->findByDay($user, 'Tuesday')[0][1];
        $weekDrinks['wednesday'] = (int)$this->drinkRepository->findByDay($user, 'Wednesday')[0][1];
        $weekDrinks['thursday']  = (int)$this->drinkRepository->findByDay($user, 'Thursday')[0][1];
        $weekDrinks['friday']    = (int)$this->drinkRepository->findByDay($user, 'Friday')[0][1];
        $weekDrinks['saturday']  = (int)$this->drinkRepository->findByDay($user, 'Saturday')[0][1];
        $weekDrinks['sunday']    = (int)$this->drinkRepository->findByDay($user, 'Sunday')[0][1];

        return $weekDrinks;
    }

    // Get ratio of advantages and inconvenients
    public function getRatioAdvantageInconvenient($user)
    {
        $advantagesUser    = $this->argRepo->findAdvantagesByUser($user);
        $inconvenientsUser = $this->argRepo->findInconvenientsByUser($user);
        $totalArgUsers = count($advantagesUser) + count($inconvenientsUser);

        $ratioAdvantageInconvenient = [];

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

        $ratioAdvantageInconvenient['advantage'] = $xAdvantageUser;
        $ratioAdvantageInconvenient['inconvenient'] = $xInconvenientUser;

        return $ratioAdvantageInconvenient;
    }
}