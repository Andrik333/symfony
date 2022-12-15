<?php

namespace App\Repository\Moex;

use App\Entity\Moex\FullOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FullOrder>
 *
 * @method FullOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method FullOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method FullOrder[]    findAll()
 * @method FullOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FullOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FullOrder::class);
    }

    public function add(FullOrder $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FullOrder $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function insertData(array $data): void
    {
        $em = $this->getEntityManager();
        $i = 0;
        foreach ($data as $row) {
            $i++;
            $order = new FullOrder;
            $order->setNo($row[0]);
            $order->setSecCode($row[1]);
            $order->setBuysell($row[2]);
            $order->setTime((\DateTime::createFromFormat('Hisu', $row[3]))->format('H:m:s.u'));
            $order->setOrderNo($row[4]);
            $order->setAction($row[5]);
            $order->setPrice($row[6]);
            $order->setVolume($row[7]);
            $order->setTradeNo($row[8] == '' ? null : $row[8]);
            $order->setTradePrice($row[9] == '' ? null : $row[9]);
            $em->persist($order);
            if ($i == 50) {
                $i = 0;
                $em->flush();
                $em->clear();
            }
        }
        $em->flush();
        $em->clear();
    }

    public function getFullOrderPage(int $limit, int $page = 1): array
    {
        return $this->createQueryBuilder('fo')
           ->orderBy('fo.id', 'ASC')
           ->setMaxResults($limit)
           ->setFirstResult($limit * ($page - 1))
           ->getQuery()
           ->getResult()
       ;
    }

    public function getCountPage(int $limit): int
    {
        $count = $this->createQueryBuilder('fo')
            ->select('count(fo.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return ceil($count/$limit);
    }

//    /**
//     * @return FullOrder[] Returns an array of FullOrder objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FullOrder
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
