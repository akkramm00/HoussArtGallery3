<?php

namespace App\Repository;

use App\Entity\Colletion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Colletion>
 *
 * @method Colletion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Colletion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Colletion[]    findAll()
 * @method Colletion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColletionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Colletion::class);
    }
}
