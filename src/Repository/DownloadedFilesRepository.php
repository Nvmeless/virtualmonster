<?php

namespace App\Repository;

use App\Entity\DownloadedFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DownloadedFiles>
 *
 * @method DownloadedFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method DownloadedFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method DownloadedFiles[]    findAll()
 * @method DownloadedFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DownloadedFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DownloadedFiles::class);
    }

//    /**
//     * @return DownloadedFiles[] Returns an array of DownloadedFiles objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DownloadedFiles
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
