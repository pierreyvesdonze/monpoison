<?php

namespace App\Repository;

use App\Entity\Sober;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sober|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sober|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sober[]    findAll()
 * @method Sober[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sober::class);
    }

    /**
     * @return Sober[] Returns an array of Drink objects
     */

    public function findByUser($user)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.user = :val')
            ->setParameter('val', $user)
            ->orderBy('s.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}