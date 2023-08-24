<?php

namespace App\Repository;

use App\Entity\UserProfessional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProfessional>
 *
 * @method UserProfessional|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfessional|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfessional[]    findAll()
 * @method UserProfessional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProfessionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProfessional::class);
    }

    public function findByProfessionalId(int $professionalId): array
    {
        return $this->createQueryBuilder('up')
            ->innerJoin('up.userProfessionalProfessionals', 'upp')
            ->andWhere('upp.professional = :professionalId')
            ->setParameter('professionalId', $professionalId)
            ->getQuery()
            ->getResult();
    }
}