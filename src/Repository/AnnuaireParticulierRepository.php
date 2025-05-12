<?php

namespace App\Repository;

use App\Entity\AnnuaireParticulier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnnuaireParticulier>
 *
 * @method AnnuaireParticulier|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnuaireParticulier|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnuaireParticulier[]    findAll()
 * @method AnnuaireParticulier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnuaireParticulierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnuaireParticulier::class);
    }

//    /**
//     * @return AnnuaireParticulier[] Returns an array of AnnuaireParticulier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AnnuaireParticulier
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
