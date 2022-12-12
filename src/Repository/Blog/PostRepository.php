<?php

namespace App\Repository\Blog;

use App\Entity\Blog\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function add(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllForIndex(int $limit = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p, c')
            ->leftJoin('p.comments', 'c')
            ->orderBy('p.created', 'DESC');

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function getTags()
    {
        $blogTags = $this->createQueryBuilder('b')
                        ->select('b.tags')
                        ->getQuery()
                        ->getResult();

        $tags = [];
        foreach ($blogTags as $blogTag)
        {
            $tags = array_merge(explode(",", $blogTag['tags']), $tags);
        }

        $tags = array_map('trim', $tags);

        return $tags;
    }

    public function getTagWeights($tags)
    {
        $tagWeights = [];

        if (empty($tags)) {
            return $tagWeights;
        }

        foreach ($tags as $tag) {
            $tagWeights[$tag] = (isset($tagWeights[$tag])) ? $tagWeights[$tag] + 1 : 1;
        }

        uksort($tagWeights, function() {
            return rand() > rand();
        });

        $max = max($tagWeights);

        $multiplier = ($max > 5) ? 5 / $max : 1;

        foreach ($tagWeights as &$tag) {
            $tag = ceil($tag * $multiplier);
        }

        return $tagWeights;
    }

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
