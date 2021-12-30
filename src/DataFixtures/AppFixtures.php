<?php

namespace App\DataFixtures;

use App\Entity\Alcool;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager as PersistenceObjectManager;

class AppFixtures extends Fixture
{
    //php bin/console doctrine:fixtures:load
    public function load(PersistenceObjectManager $manager)
    {
        $beer = new Alcool;
        $beer->setTitle("Bière");
        $beer->setDegree(5.5);
        $beer->setQuantity("25 cl");
        $beer->setImgPath('/monpoison/public/assets/images/beer.png');
        $manager->persist($beer);
        
        $wine = new Alcool;
        $wine->setTitle('Vin');
        $wine->setDegree(13);
        $wine->setQuantity("12 cl");
        $wine->setImgPath('/monpoison/public/assets/images/wine.png');
        $manager->persist($wine);
        
        $spiritus = new Alcool;
        $spiritus->setTitle("Alcool fort");
        $spiritus->setDegree(40);
        $spiritus->setQuantity("4 cl");
        $spiritus->setImgPath('/monpoison/public/assets/images/whisky.png');
        $manager->persist($spiritus);

        $manager->flush();
    }
}
