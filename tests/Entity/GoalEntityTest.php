<?php

namespace App\Tests\Entity;

use App\Entity\Goal;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * php bin/phpunit tests/Entity/GoalEntityTest.php
 */
class GoalEntityTest extends TestCase
{
    public function testIsTrue()
    {
        $goal = new Goal();
        $goal
            ->setContent('content')
            ->setIsAchieved(false)
            ->setUser($user = new User());

        $this->assertTrue($goal->getContent() === 'content');
        $this->assertTrue($goal->getIsAchieved() === false);
        $this->assertTrue($goal->getUser() === $user);
    }

    public function testIsFalse()
    {
        $goal = new Goal();
        $goal
            ->setContent('content')
            ->setIsAchieved(false)
            ->setUser($user = new User());

        $this->assertFalse($goal->getContent() === 'pas content');
        $this->assertFalse($goal->getIsAchieved() === true);
        $this->assertFalse($goal->getUser() === 'Jean-Patrick');
    }
}
