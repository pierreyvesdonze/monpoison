<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager as PersistenceObjectManager;

class BadgeFixtures extends Fixture
{
    //php bin/console doctrine:fixtures:load --env=test
    public function load(PersistenceObjectManager $manager)
    {
        for ($i = 0; $i < 35; $i++) {
            $badge = new Badge();
            $badge->setTitle('0'.$i);
            $badge->setPath("/monpoison/public/assets/images/badges/'.$i'.'png");

            $manager->persist($badge);
        }
        $manager->flush();
    }
}
