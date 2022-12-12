<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function add(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCommentsForBlog($blogId, $approved = null)
    {
        $qb = $this->createQueryBuilder('c')
                   ->select('c')
                   ->where('c.blog = :blog_id')
                   ->orderBy('c.created', 'DESC')
                   ->setParameter('blog_id', $blogId);

        if (!is_null($approved)) {
            $qb->andWhere('c.approved = :approved')
               ->setParameter('approved', $approved);
        }
            

        return $qb->getQuery()
                  ->getResult();
    }

    public function getLatestComments($limit = 5)
    {
        $qb = $this->createQueryBuilder('c')
                    ->select('c')
                    ->addOrderBy('c.id', 'DESC');

        if (!is_null($limit))
            $qb->setMaxResults($limit);

        return $qb->getQuery()
                ->getResult();
    }

//    /**
//     * @return Comment[] Returns an array of Comment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Comment
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
