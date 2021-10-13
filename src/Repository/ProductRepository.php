<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    
    /*public function findByName($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }*/
    
    // Barre de recherche
    // public function searchProduct($filter){
    //     $objectArray = $this->createQueryBuilder('p')
    //     ->join('p.brand', 'm')
    //     ->join('p.sub_category', 'sb');
        
    //     if(!is_null($filter['searchText'])){
    //         $objectArray->where('p.name LIKE :name');
    //         $objectArray->setParameter(':name', '%'.$filter['searchText'].'%');
    //     }

    //     if(!is_null($filter['brand'])){
    //         $objectArray->andWhere('m = :brand')
    //         ->setParameter('brand', $filter['brand']);
    //     }

    //     if(!is_null($filter['subCategory'])){
    //         $objectArray->andWhere('sb = :subCategory')
    //         ->setParameter('subCategory', $filter['subCategory']);
    //     }

    //     return $objectArray->getQuery()->getResult();
    // }
    
    // Afficher le produit par ropport à sa catégorie parent
    public function getProductByCategory($value)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.sub_category', 's')
            ->andWhere('s = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    // Afficher des produits qui appartient à la même sous-categorie
    public function similarProduct($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.sub_category = :val')
            ->setParameter('val', $value)
            ->orderBy('RAND()')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    // affiche tous les produits ou l'id_sousCategory de produit soit égale a l'id de la sous-category

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
