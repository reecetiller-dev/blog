<?php

namespace App\Repository;

use App\Entity\BlogPostHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogPostHistory>
 *
 * @method BlogPostHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPostHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPostHistory[]    findAll()
 * @method BlogPostHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPostHistory::class);
    }

    public function add(BlogPostHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BlogPostHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllPostsHistoryByBlogPostId(int $id,int $page = 1, int $limit = 20): array
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder
            ->select('bph')
            ->from('App:BlogPostHistory', 'bph')
            ->where('bph.blogPost = :id')
            ->orderBy('bph.id', 'DESC')
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getResult();
    }
}
