<?php

namespace App\Tests\Entity;

use App\Entity\ArgumentUser;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * php bin/phpunit tests/Entity/ArgumentUserEntityTest.php
 */
class ArgumentUserEntityTest extends TestCase
{
    public function testIsTrue()
    {
        $argumentUser = new ArgumentUser();
        $argumentUser
            ->setType('0')
            ->setUser($user = new User())
            ->setContent('contenu');

        $this->assertTrue($argumentUser->getType() === '0');
        $this->assertTrue($argumentUser->getUser() === $user);
        $this->assertTrue($argumentUser->getContent() === 'contenu');
    }

    public function testIsFalse()
    {
        $argumentUser = new ArgumentUser();
        $argumentUser
            ->setType(0)
            ->setUser($user = new User())
            ->setContent('contenu');

        $this->assertFalse($argumentUser->getType() === '22');
        $this->assertFalse($argumentUser->getUser() === 'Jean');
        $this->assertFalse($argumentUser->getContent() === 'pas de contenu');
    }
}
