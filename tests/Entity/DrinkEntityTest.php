<?php

namespace App\Tests\Entity;

use App\Entity\Alcool;
use App\Entity\Drink;
use App\Entity\User;
use PHPUnit\Framework\TestCase;


/**
 * php bin/phpunit tests/Entity/DrinkEntityTest.php
 */
class DrinkEntityTest extends TestCase
{
    public function testIsTrue()
    {
        $drink = new Drink();
        $drink
            ->setAlcool($alcool = new Alcool)
            ->setUser($user = new User())
            ->setQuantity(1)
            ->setDate($date = new \DateTime())
            ->setCost(2.50);

        $this->assertTrue($drink->getAlcool() === $alcool);
        $this->assertTrue($drink->getUser() === $user);
        $this->assertTrue($drink->getQuantity() === 1);
        $this->assertTrue($drink->getDate() === $date);
        $this->assertTrue($drink->getCost() === 2.50);
    }

    public function testIsFalse()
    {
        $drink = new drink();
        $drink
            ->setAlcool($alcool = new Alcool)
            ->setUser($user = new User())
            ->setQuantity(1)
            ->setDate($date = new \DateTime())
            ->setCost(2.50);


        $this->assertFalse($drink->getAlcool() === false);
        $this->assertFalse($drink->getUser() === false);
        $this->assertFalse($drink->getQuantity() === 5);
        $this->assertFalse($drink->getDate() === false);
        $this->assertFalse($drink->getCost() === 0);
    }
}
