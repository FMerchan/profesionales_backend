<?php

namespace App\Repository;

use App\Entity\LegalDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LegalDocument>
 *
 * @method LegalDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method LegalDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method LegalDocument[]    findAll()
 * @method LegalDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LegalDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LegalDocument::class);
    }
}