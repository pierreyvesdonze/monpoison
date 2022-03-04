<?php

namespace App\Repository;

use App\Entity\Badge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Badge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Badge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Badge[]    findAll()
 * @method Badge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Badge::class);
    }

    /**
     * @return Badge[] Returns an array of Badge objects
     */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.user', 'u')
            ->where('u.id = :user')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Badge[] Returns an array of Badge objects
     */
    public function findBySoberDay($totalSobers)
    {
        return $this->createQueryBuilder('b')
            ->where('b = :badge')
            ->setParameter('badge', $totalSobers)
            ->getQuery()
            ->getResult();
    }
}
