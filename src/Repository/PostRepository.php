<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;


/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginatorInterface

    ) {
        parent::__construct($registry, Post::class);
    }

    /**
     * Get published posts
     *
     * @param integer $page
     * @param Category|null $category
     * @return PaginationInterface
     */
    public function findPublished(int $page, ?Category $category = null): PaginationInterface
    {
        $data = $this->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.categories', 'c')
            ->where('p.state LIKE :state')
            ->setParameter('state', '%STATE_PUBLISHED%')
            ->addOrderBy('p.createdAt', 'DESC');

        if ($category) {
            $data->andWhere('c.id = :category')
                ->setParameter('category', $category->getId());
        }

        $query = $data->getQuery();

        $posts = $this->paginatorInterface->paginate($query, $page, 9);

        return $posts;
    }

    public function findPublicPost(?int $nbPost): array
    {
        $queryBuilder = $this->createQueryBuilder('p'); // Utilisez "createQueryBuilder" au lieu de "creatQueryBuilder"
        $queryBuilder->select('p')
            ->where('p.state LIKE :state')
            ->setParameter('state', '%STATE_PUBLISHED%')
            ->orderBy('p.createdAt', 'DESC');

        if (!$nbPost !== 0 || !$nbPost !== null) {
            $queryBuilder->setMaxResults($nbPost);
        }

        return $queryBuilder->getQuery()
            ->getResult();
    }
}
