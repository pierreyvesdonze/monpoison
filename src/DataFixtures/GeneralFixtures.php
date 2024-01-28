<?php

namespace App\DataFixtures;

use App\Entity\Alcool;
use App\Entity\Badge;
use App\Entity\User;
use App\Entity\Drink;
use App\Entity\Money;
use App\Entity\Sober;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager as PersistenceObjectManager;

class GeneralFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasherInterface
    ) {
    }

    //php bin/console doctrine:fixtures:load --env=dev
    public function load(
        PersistenceObjectManager $manager
    ) {
        // ALCOOL
        $beer = new Alcool();
        $beer->setTitle("Bière");
        $beer->setDegree(5.5);
        $beer->setQuantity("25 cl");
        $beer->setImgPath('/assets/images/beer.png');
        $manager->persist($beer);

        $wine = new Alcool();
        $wine->setTitle('Vin');
        $wine->setDegree(13);
        $wine->setQuantity("12 cl");
        $wine->setImgPath('/assets/images/wine.png');
        $manager->persist($wine);

        $spiritus = new Alcool();
        $spiritus->setTitle("Alcool fort");
        $spiritus->setDegree(40);
        $spiritus->setQuantity("4 cl");
        $spiritus->setImgPath('/assets/images/whisky.png');
        $manager->persist($spiritus);

        // BADGES
        for ($i = 0; $i < 35; $i++) {
            $badge = new Badge();
            $badge->setTitle('0' . $i);
            $badge->setPath("/assets/images/badges/$i.png");

            $manager->persist($badge);
        }

        // MONEY
        $money = new Money;
        $money->setAmount(158);
        $manager->persist($money);

        // USER
        $user = new User();
        $user->setEmail('test@test.test');
        $user->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                $user,
                'testtest'
            )
        );
        $user->setPseudo('test');
        $user->setIsDeleted(false);
        $user->setIsSubscribed(true);
        $user->setAutoSober(true);
        $user->addMoney($money);
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $manager->flush();

        // DRINKS
        $alcools = $manager->getRepository(\App\Entity\Alcool::class)->findAll();

        for ($i = 0; $i < 50; $i++) {
            $drink = new Drink();
            $drink->setAlcool($alcools[array_rand($alcools)]);
            $drink->setUser($user);
            $drink->setQuantity(random_int(1, 10));

            $randomTimestamp = mt_rand(strtotime('2023-11-01'), strtotime('2023-12-01'));
            $randomDate = (new \DateTime())->setTimestamp($randomTimestamp);
            $drink->setDate($randomDate);

            $drink->setCost(random_int(100, 1000) / 100);

            $manager->persist($drink);
        }

        // SOBER
        for ($i = 0; $i < 20; $i++) {
            $sober = new Sober;
            $sober->setUser($user);
            $soberDate = new \DateTime('2024-01-01');
            $soberDate->modify("+$i days");

            $sober->setDate($soberDate);

            $manager->persist($sober);
        }

        $manager->flush();
    }
}
