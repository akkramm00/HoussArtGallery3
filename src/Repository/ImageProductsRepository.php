<?php

namespace App\Repository;

use App\Entity\ImageProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImageProducts>
 *
 * @method ImageProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageProducts[]    findAll()
 * @method ImageProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageProducts::class);
    }

//    /**
//     * @return ImageProducts[] Returns an array of ImageProducts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImageProducts
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
