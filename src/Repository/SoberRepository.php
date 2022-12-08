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
     * @return Sober[] Returns an array of Sobers objects
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

    /**
     * @return Sober[] Returns an array of Sobers objects
     */
    public function findDatesByUser($user, $orderBy)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.user = :val')
            ->setParameter('val', $user)
            ->select('s.date')
            ->orderBy('s.date', $orderBy)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Sober[] Returns an array of Sobers objects
     */
    public function findDatesGreaterThanByUser($user, $last)
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.user = :val')
            ->setParameter('val', $user);
        
            if ($last !== null) {
                $qb = $qb->andWhere('s.date > :last')
                ->setParameter('last', $last);
        }
        $qb = $qb
            ->select('s.date')
            ->orderBy('s.date', 'DESC')
            ->getQuery()
            ->getResult();
        
            return $qb;
    }

    /**
     * @return Sober[] Returns an array of Drink objects
     */
    public function findByUserAndByDate($user, $date)
    {
        return $this->createQueryBuilder('s')
            ->where('s.user = :user')
            ->andWhere('s.date = :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date)
            ->orderBy('s.date', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
