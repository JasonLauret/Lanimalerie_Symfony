<?php

namespace App\Repository;

use App\Entity\OrderProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderProduct[]    findAll()
 * @method OrderProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProduct::class);
    }

    // /**
    //  * @return OrderProduct[] Returns an array of OrderProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function  getBestSales()
    {
        return $this->createQueryBuilder("o")
            ->groupBy("o.product")
            ->setMaxResults(3)
            ->orderBy("COUNT(o.product)", "DESC")
            ->getQuery()
            ->getResult();
    }


    // La requette qui suis ne marche pas
    // public function productByOrder($value)
    // {
    //     return $this->createQueryBuilder('op')
    //         ->innerJoin('op.order', 'o')
    //         ->where('op.commande = o.id')
    //         ->andWhere('o.user = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    // affiche * from orderproduct where commande_id = id(order) andWhere id(order) = 2




    /*
    public function findOneBySomeField($value): ?OrderProduct
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
