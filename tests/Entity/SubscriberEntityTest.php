<?php

namespace App\Tests\Entity;

use App\Entity\Subscriber;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * php bin/phpunit tests/Entity/SubscriberEntityTest.php
 */
class SubscriberEntityTest extends TestCase
{
    public function testIsTrue()
    {
        $subscriber = new Subscriber();
        $subscriber->setEmail('email@email.fr');

        $this->assertTrue($subscriber->getEmail() === 'email@email.fr');
    }

    public function testIsFalse()
    {
        $subscriber = new Subscriber();
        $subscriber->setEmail('email@email.fr');

        $this->assertFalse($subscriber->getEmail() === 'email2@email.fr');
    }
}
