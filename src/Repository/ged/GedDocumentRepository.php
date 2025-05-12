<?php

namespace App\Repository\ged;

use App\Entity\ged\GedDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GedDocument>
 *
 * @method GedDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method GedDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method GedDocument[]    findAll()
 * @method GedDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GedDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GedDocument::class);
    }
    public function maxId(): ?int
    {
        $qb = $this->createQueryBuilder('u');

        // On cherche la valeur maximale de l'id
        $qb->select('MAX(u.id) as maxId');

        // On récupère le résultat
        $result = $qb->getQuery()->getOneOrNullResult();

        // Si un résultat est trouvé, on retourne la valeur de maxId
        return $result ? $result['maxId']+1 : 100;
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
}