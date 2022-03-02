<?php

namespace App\Service;

use App\Repository\SoberRepository;
use App\Repository\DrinkRepository;
use App\Repository\ArgumentUserRepository;
use App\Repository\GoalRepository;

class UserStatsService
{
    public function __construct(
        private SoberRepository $soberRepository,
        private DrinkRepository $drinkRepository,
        private ArgumentUserRepository $argRepo,
        private GoalRepository $goalRepository
    ) {
    }

    // Get dates sorted by ASC to calculate longest period of sobriety
    public function getMaxSobrietyPeriod($user)
    {
        $sobersDates = $this->soberRepository->findDatesByUser($user);

        $period            = 0;
        $periodMax         = 0;
        $previousDay       = null;
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

    // Get encouragements day by then month by month
    public function getEncouragement($user)
    {
        $totalSober = $this->getMaxSobrietyPeriod($user);
        
        $encouragementsJson = file_get_contents('../public/assets/json/encouragements.json');
        $encouragementsArray = json_decode($encouragementsJson);

        if ($totalSober === 1) {
            return $encouragementsArray->duration->{'1-day'};
        } elseif ($totalSober === 2) {
            return $encouragementsArray->duration->{'2-days'};
        } elseif ($totalSober === 3) {
            return $encouragementsArray->duration->{'3-days'};
        } elseif ($totalSober === 4) {
            return $encouragementsArray->duration->{'4-days'};
        } elseif ($totalSober === 5) {
            return $encouragementsArray->duration->{'5-days'};
        } elseif ($totalSober === 6) {
            return $encouragementsArray->duration->{'6-days'};
        } elseif ($totalSober === 7) {
            return $encouragementsArray->duration->{'1-week'};
        } elseif ($totalSober === 14) {
            return $encouragementsArray->duration->{'2-weeks'};
        } elseif ($totalSober === 21) {
            return $encouragementsArray->duration->{'3-weeks'};
        } elseif ($totalSober === 30 || $totalSober === 31) {
            return $encouragementsArray->duration->{'1-month'};
        } elseif ($totalSober === 60 || $totalSober <= 61) {
            return $encouragementsArray->duration->{'2-months'};
        } elseif ($totalSober === 90 && $totalSober <= 91) {
            return $encouragementsArray->duration->{'3-months'};
        } elseif ($totalSober === 120 && $totalSober <= 121) {
            return $encouragementsArray->duration->{'4-months'};
        } elseif ($totalSober === 151 && $totalSober <= 152) {
            return $encouragementsArray->duration->{'5-months'};
        } elseif ($totalSober === 182 && $totalSober <= 183) {
            return $encouragementsArray->duration->{'6-months'};
        } elseif ($totalSober === 212 && $totalSober <= 213) {
            return $encouragementsArray->duration->{'7-months'};
        } elseif ($totalSober === 243 && $totalSober <= 244) {
            return $encouragementsArray->duration->{'8-months'};
        } elseif ($totalSober === 273 && $totalSober <= 274) {
            return $encouragementsArray->duration->{'9-months'};
        } elseif ($totalSober === 304 && $totalSober <= 305) {
            return $encouragementsArray->duration->{'10-months'};
        } elseif ($totalSober === 334 && $totalSober <= 335) {
            return $encouragementsArray->duration->{'11-months'};
        } elseif ($totalSober === 365) {
            return $encouragementsArray->duration->{'1-year'};
        }
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
        $statsArray['xSpiritus'] = $xSpiritus;

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
        $totalArgUsers     = count($advantagesUser) + count($inconvenientsUser);

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

        $ratioAdvantageInconvenient['advantage']    = $xAdvantageUser;
        $ratioAdvantageInconvenient['inconvenient'] = $xInconvenientUser;

        return $ratioAdvantageInconvenient;
    }

    public function getGoals($user) {
        
        $positiveGoals = count($this->goalRepository->findPositiveGoalsByUser($user));
        $totalGoals = count($this->goalRepository->getTotalGoals($user));
        $goalRatio = [];

        $goalRatio['positive'] = $positiveGoals;
        $goalRatio['total'] = $totalGoals;

        return $goalRatio;
    }
}
