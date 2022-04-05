<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;


/**
 * php bin/phpunit tests/Entity/UserEntityTest.php
 */
class UserEntityTest extends TestCase
{
    public function testIsTrue()
    {
        $user = new User();
        $user
            ->setEmail('usertest@test.com')
            ->setPassword('password')
            ->setPseudo('pseudo')
            ->setIsDeleted(false)
            ->setIsSubscribed(true)
            ->setRoles(['ROLE_USER'])
            ->setHomepage('home')
            ->setAutoSober(true);

            $this->assertTrue($user->getEmail() === 'usertest@test.com');
            $this->assertTrue($user->getPassword() === 'password');
            $this->assertTrue($user->getPseudo() === 'pseudo');
            $this->assertTrue($user->isDeleted() === false);
            //$this->assertTrue($user->setIsSubscribed(true) === true);
            $this->assertTrue($user->getRoles() === ['ROLE_USER']);
            $this->assertTrue($user->getHomepage() === 'home');
        $this->assertTrue($user->getAutoSober() === true);
    }

    public function testIsFalse()
    {
        $user = new User();
        $user
            ->setEmail('usertest@test.com')
            ->setPassword('password')
            ->setPseudo('pseudo')
            ->setIsDeleted(false)
            ->setIsSubscribed(true)
            ->setRoles(['ROLE_USER'])
            ->setHomepage('home')
            ->setAutoSober(true);

        $this->assertFalse($user->getEmail() === 'false@test.com');
        $this->assertFalse($user->getPassword() === 'false');
        $this->assertFalse($user->getPseudo() === 'false');
        $this->assertFalse($user->isDeleted() === true);
        $this->assertFalse($user->setIsSubscribed(false) === true);
        $this->assertFalse($user->getRoles() === ['ROLE_ADMIN']);
        $this->assertFalse($user->getHomepage() === 'homepage');
        $this->assertFalse($user->getAutoSober() === false);
    }
}
