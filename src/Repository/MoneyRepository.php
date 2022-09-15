<?php

namespace App\Repository;

use App\Entity\Money;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Money|null find($id, $lockMode = null, $lockVersion = null)
 * @method Money|null findOneBy(array $criteria, array $orderBy = null)
 * @method Money[]    findAll()
 * @method Money[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoneyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Money::class);
    }

    /**
     * @return Money[] Returns an array of Money objects
     */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.user = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult();
    }
}
