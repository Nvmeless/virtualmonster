<?php

namespace App\Repository;

use App\Entity\Monstredex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Monstredex>
 *
 * @method Monstredex|null find($id, $lockMode = null, $lockVersion = null)
 * @method Monstredex|null findOneBy(array $criteria, array $orderBy = null)
 * @method Monstredex[]    findAll()
 * @method Monstredex[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonstredexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Monstredex::class);
    }

//    /**
//     * @return Monstredex[] Returns an array of Monstredex objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Monstredex
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
