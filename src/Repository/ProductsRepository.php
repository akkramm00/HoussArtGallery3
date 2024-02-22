<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }
    /*********************************************************************************************************** */
    /**
     * This method allow us to find public pproducts based on number of products
     *
     * @param integer $nbProducts
     * @return array
     */
    public function findPublicProducts(?int $nbProducts): array
    {
        $queryBuilder = $this->createQueryBuilder('p'); // Utilisez "createQueryBuilder" au lieu de "creatQueryBuilder"
        $queryBuilder->select('p')
            ->where('p.isPublic = 1')
            ->orderBy('p.createdAt', 'DESC');

        if (!$nbProducts !== 0 || !$nbProducts !== null) {
            $queryBuilder->setMaxResults($nbProducts);
        }

        return $queryBuilder->getQuery()
            ->getResult();
    }
}
