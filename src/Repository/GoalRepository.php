<?php

namespace App\Repository;

use App\Entity\Goal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Goal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Goal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Goal[]    findAll()
 * @method Goal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Goal::class);
    }

     /**
      * @return Goal[] Returns an array of Goal objects
    */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.user = :val')
            ->setParameter('val', $user)
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Goal Returns Goal object
     */
    public function finOnedById($goalId)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.id = :val')
            ->setParameter('val', $goalId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Goal Returns Goal object
     */
    public function findPositiveGoalsByUser($user)
    {
        return $this->createQueryBuilder('g')
            ->where('g.isAchieved = :pos')
            ->andWhere('g.user = :val')
            ->setParameter('val', $user)
            ->setParameter('pos', 1)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Goal Returns Goal object
     */
    public function getTotalGoals($user)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.user = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult();
    }
}
