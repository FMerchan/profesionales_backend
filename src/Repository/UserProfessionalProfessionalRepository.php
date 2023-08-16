<?php

namespace App\Repository;

use App\Entity\UserProfessionalProfessional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserProfessionalProfessionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProfessionalProfessional::class);
    }
}