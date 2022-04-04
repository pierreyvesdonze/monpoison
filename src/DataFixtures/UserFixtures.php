<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager as PersistenceObjectManager;

class UserFixtures extends Fixture
{
    //php bin/console doctrine:fixtures:load
    public function load(PersistenceObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User;
            $user->setEmail('user' . $i . '@test.test');
            $user->setPassword('testtest');
            $user->setPseudo('test'.$i);
            $user->setIsDeleted(false);
            $user->setIsSubscribed(true);
            $user->setAutoSober(true);
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $manager->flush();
        }
    }
}
