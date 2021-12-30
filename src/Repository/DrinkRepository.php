<?php

namespace App\Repository;

use App\Entity\Drink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Drink|null find($id, $lockMode = null, $lockVersion = null)
 * @method Drink|null findOneBy(array $criteria, array $orderBy = null)
 * @method Drink[]    findAll()
 * @method Drink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drink::class);
    }

    /**
     * @return Drink[] Returns an array of Drink objects
     */

    public function findByUser($user)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.user = :val')
            ->setParameter('val', $user)
            ->orderBy('d.date', 'DESC')
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Drink
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
