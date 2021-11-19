<?php

namespace App\Repository;

use App\Entity\Statistical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Statistical|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statistical|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statistical[]    findAll()
 * @method Statistical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatisticalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistical::class);
    }

    // /**
    //  * @return Statistical[] Returns an array of Statistical objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Statistical
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
