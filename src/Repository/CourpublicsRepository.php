<?php

namespace App\Repository;

use App\Entity\Courpublics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Courpublics|null find($id, $lockMode = null, $lockVersion = null)
 * @method Courpublics|null findOneBy(array $criteria, array $orderBy = null)
 * @method Courpublics[]    findAll()
 * @method Courpublics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourpublicsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courpublics::class);
    }

    // /**
    //  * @return Courpublics[] Returns an array of Courpublics objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Courpublics
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
