<?php

namespace App\Repository;

use App\Entity\Matierepublics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Matierepublics|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matierepublics|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matierepublics[]    findAll()
 * @method Matierepublics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatierepublicsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matierepublics::class);
    }

    // /**
    //  * @return Matierepublics[] Returns an array of Matierepublics objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Matierepublics
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
