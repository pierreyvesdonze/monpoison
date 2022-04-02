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

    /**
     * @return Drink[] Returns an array of Drink objects
     */
    public function findDatesByUser($user)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.user = :val')
            ->setParameter('val', $user)
            ->select('d.date')
            ->orderBy('d.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Drink[] Returns an array of last week Drink objects
     */
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

    /**
     * @return Drink[] Returns an array of last week Drink objects
     */
    public function findLastDayDrinks($user)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->andWhere('d.date BETWEEN :begin AND :end')
            ->setParameter('begin', new \DateTime('-1 day'))
            ->setParameter('end', new \DateTime('now'))
            ->setParameter('user', $user)
            ->select('SUM(d.quantity)')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Cost on a week
     */
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

    /**
     * @return Drink['beer'] Returns an array of Drink beer objects
     */
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

    /**
     * @return Drink['wine'] Returns an array of Drink wine objects
     */
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

    /**
     * @return Drink['spiritus'] Returns an array of Drink spiritus objects
     */
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

    /**
     * @return SUM of Drink[] Returns an array of Drink objects
     */
    public function findByDay($user, $day)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->andWhere('DayName(d.date) = :day')
            ->setParameter('user', $user)
            ->setParameter('day', $day)
            ->select('SUM(d.quantity)')
            ->getQuery()
            ->getResult();
    }

     /**
     * @return SUM of Drink[] Returns an array of Drink objects
     */
    public function findByUserAndByDate($user, $date)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->andWhere('d.date = :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date)
            ->select('SUM(d.quantity)')
            ->getQuery()
            ->getResult();
    }

     /**
     * @return SUM of Drink[] Returns an array of Drink objects
     */
    public function findDrinkOfTheDay($user, $date)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->andWhere('d.date = :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return SUM of Drink[] Returns an array of Drink objects
     */
    public function findExistingDrink($user, $day, $alcool)
    {
        return $this->createQueryBuilder('d')
            ->where('d.user = :user')
            ->andWhere('d.date = :day')
            ->andWhere('d.alcool = :alcool')
            ->setParameter('user', $user)
            ->setParameter('day', $day)
            ->setParameter('alcool', $alcool)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Drink Returns Last Drink object
     */
    public function findLastDrink($user) 
    {
        return $this->createQueryBuilder("d")
            ->where('d.user = :user')
            ->orderBy("d.id", "DESC")
            ->setParameter('user', $user)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
