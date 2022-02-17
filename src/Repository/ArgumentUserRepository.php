<?php

namespace App\Repository;

use App\Entity\ArgumentUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArgumentUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArgumentUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArgumentUser[]    findAll()
 * @method ArgumentUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArgumentUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArgumentUser::class);
    }

    /**
     * @return ArgumentUser[] Returns an array of ArgumentUser objects
     */
    public function findAllByUser($user)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ArgumentUser[] Returns an array of ArgumentUser objects
     */
    public function findAdvantagesByUser($user)
    {
        return $this->createQueryBuilder('a')
            ->where('a.type = 0')
            ->andWhere('a.user = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return ArgumentUser[] Returns an array of ArgumentUser objects
     */
    public function findInconvenientsByUser($user)
    {
        return $this->createQueryBuilder('a')
            ->where('a.type = 1')
            ->andWhere('a.user = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult();
    }
}
