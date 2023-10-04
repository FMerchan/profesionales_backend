<?php

namespace App\Repository;

use App\Entity\Turn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Turn>
 *
 * @method Turn|null find($id, $lockMode = null, $lockVersion = null)
 * @method Turn|null findOneBy(array $criteria, array $orderBy = null)
 * @method Turn[]    findAll()
 * @method Turn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurnRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Turn::class);
    }

    public function findByOfficeIdAndDates($officeId, $startDate, $endDate) {
        return $this->createQueryBuilder('t')
            ->where('t.office = :officeId')
            ->andWhere('t.date BETWEEN :startDate AND :endDate')
            ->setParameter('officeId', $officeId)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

    public function findCloseTurns(int $userProfessionalId) {
        $fecha = new \DateTime();
        return $this->createQueryBuilder('t')
            ->where('t.userProfessional = :userProfessional')
            ->andWhere('(t.date <= :date OR t.cancelled = 1)')
            ->setParameter('userProfessional', $userProfessionalId)
            ->setParameter('date', $fecha->format('Y-m-d 23:59'))
            ->getQuery()
            ->getResult();
    }

    public function findOpenTurns(int $userProfessionalId) {
        $fecha = new \DateTime();

        // Obtener los IDs de las oficinas asociadas al userProfessional
        $officeIds = $this->createQueryBuilder('o')
            ->select('o.id')
            ->where('o.userProfessional = :userProfessionalId')
            ->setParameter('userProfessionalId', $userProfessionalId)
            ->getQuery()
            ->getResult(); // Obtener un array de IDs

        // Extraer los IDs en un array simple
        $officeIdsArray = array_map(function($result) {
            return $result['id'];
        }, $officeIds);

        // Realizar una segunda consulta para obtener los turnos relacionados con las oficinas
        return $this->createQueryBuilder('t')
            ->where('t.office IN (:officeIds)')
            ->andWhere(' t.cancelled = 0')
            ->andWhere('t.date > :date')
            ->setParameter('officeIds', $officeIdsArray)
            ->setParameter('date', $fecha->format('Y-m-d 23:59'))
            ->getQuery()
            ->getResult();
    }

    public function findProfessionalTurns(int $userProfessionalId) {

    }
}