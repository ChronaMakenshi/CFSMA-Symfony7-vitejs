<?php

namespace App\Repository;

use App\Entity\CoursFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CoursFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoursFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoursFiles[]    findAll()
 * @method CoursFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoursFiles::class);
    }

    // /**
    //  * @return CoursFiles[] Returns an array of CoursFiles objects
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
    public function findOneBySomeField($value): ?CoursFiles
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
