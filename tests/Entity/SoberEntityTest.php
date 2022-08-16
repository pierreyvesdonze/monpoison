<?php

namespace App\Tests\Entity;

use App\Entity\Sober;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * php bin/phpunit tests/Entity/SoberEntityTest.php
 */
class SoberEntityTest extends TestCase
{
    public function testIsTrue()
    {
        $sober = new Sober();
        $sober
            ->setUser($user = new User())
            ->setDate($date = new \DateTime());

        $this->assertTrue($sober->getUser() === $user);
        $this->assertTrue($sober->getDate() === $date);
    }

    public function testIsFalse()
    {
        $sober = new Sober();
        $sober
            ->setUser($user = new User())
            ->setDate($date = new \DateTime());

        $this->assertFalse($sober->getUser() === false);
        $this->assertFalse($sober->getDate() === false);
    }
}
