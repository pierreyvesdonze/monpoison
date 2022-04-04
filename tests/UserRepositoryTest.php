<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    public function testCount()
    {
        self::bootKernel();
        $users = static::getContainer()->get(UserRepository::class)->count([]);
        $this->assertEquals(10, $users);
    }
}
