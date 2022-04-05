<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * php bin/phpunit tests/Repository/UserRepositoryTest.php
 */
class UserRepositoryTest extends KernelTestCase
{
    public function testCount()
    {
        self::bootKernel();
        $users = static::getContainer()->get(UserRepository::class)->count([]);
        $this->assertEquals(10, $users);
    }
}
