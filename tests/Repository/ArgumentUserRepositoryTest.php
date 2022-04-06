<?php

namespace App\Tests\Repository;

use App\Entity\ArgumentUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * php bin/phpunit tests/Repository/ArgumentUserRepositoryTest.php
 */
class ArgumentUserRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

    }

    public function testFindAllByUser()
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['id' => '18']);
        $argument = $this->entityManager
            ->getRepository(ArgumentUser::class)
            ->findAllByUser(['user' => $user]);

        $this->assertSame(['string', 'strong'], $argument->getContent());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
