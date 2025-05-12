<?php

namespace App\Repository\administratif;

use App\Entity\administratif\AnnuaireEntreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnnuaireEntreprise>
 *
 * @method AnnuaireEntreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnuaireEntreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnuaireEntreprise[]    findAll()
 * @method AnnuaireEntreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnuaireEntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnuaireEntreprise::class);
    }
	public function getNextAvailableId(): ?int // Renommé ici
    {
        $qb = $this->createQueryBuilder('u');

        // On cherche la valeur maximale de l'id
        $qb->select('MAX(u.id) as maxId');

        // On récupère le résultat
        $result = $qb->getQuery()->getOneOrNullResult();

        // Si un résultat est trouvé, on retourne la valeur de maxId
        return $result ? $result['maxId'] + 1 : 100;
    }
    public function dataTableList(): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->select('a.id, a.nom, a.add_postale, a.ville, a.pays, a.site_internet, a.uid_val, a.activite, a.client_fourniss, a.rem')
            ->where('a.valid = 1')
            ->andWhere('a.client_fourniss IN (:isClient)')
            ->setParameter('isClient', [1, 3])
            ->orderBy('a.nom', 'ASC');
    
        return $qb->getQuery()->getResult();
    }
 

//    /**
//     * @return AnnuaireEntreprise[] Returns an array of AnnuaireEntreprise objects
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

//    public function findOneBySomeField($value): ?AnnuaireEntreprise
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
