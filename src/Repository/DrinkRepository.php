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

    public function findLastWeekDrinks($user)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->andWhere('d.date BETWEEN :begin AND :end')
            ->setParameter('begin', new \DateTime('-1 week'))
            ->setParameter('end', new \DateTime('now'))
            ->setParameter('user', $user)
            ->select('SUM(d.quantity)')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLastWeekCost($user)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->andWhere('d.date BETWEEN :begin AND :end')
            ->setParameter('begin', new \DateTime('-1 week'))
            ->setParameter('end', new \DateTime('now'))
            ->setParameter('user', $user)
            ->select('SUM(d.cost)')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTotalBeer($user)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->andWhere('d.alcool = :beer')
            ->setParameter('user', $user)
            ->setParameter('beer', 4)
            ->select('SUM(d.quantity)')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTotalWine($user)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->andWhere('d.alcool = :wine')
            ->setParameter('user', $user)
            ->setParameter('wine', 5)
            ->select('SUM(d.quantity)')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTotalSpiritus($user)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->andWhere('d.alcool = :spiritus')
            ->setParameter('user', $user)
            ->setParameter('spiritus', 6)
            ->select('SUM(d.quantity)')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
