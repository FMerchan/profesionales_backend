<?php

namespace App\Service;

use App\Entity\Office;
use App\Entity\Turn;
use App\Entity\UserProfessional;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class TurnService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function createTurn(UserProfessional $userProfessional, array $data): Office
    {
        $entityManager = $this->entityManager;

        // Validar los datos antes de continuar
        $this->validateData($data);

        // Crear una nueva instancia de la entidad turno
        $turn = new turn();
        $office = $entityManager->getRepository(Office::class)->find($data['office_id']);

        if (is_null($office)) {
            throw new \InvalidArgumentException("Internal error.");
        }

        $dateTime = DateTime::createFromFormat("Y-m-d H:i", $data['date']);

        $turn->setDate($dateTime ?? null);
        $turn->setDuration($data['duration'] ?? null);

        $turn->setUserProfessional($userProfessional);
        $turn->setOffice($office);
        $this->entityManager->persist($turn);
        $this->entityManager->flush();
        return $office;
    }

    // Validación de los datos antes de guardar
    private function validateData(array $data): bool
    {
        // Validar la existencia de al menos una franja horaria
        if (empty($data['date'])) {
            throw new \InvalidArgumentException("Debe seleccionar una fecha.");
        }

        $dateTime = DateTime::createFromFormat("Y-m-d H:i", $data['date']);
        // Normaliza la hora a dos dígitos en la parte de la hora
        $normalizedHour = (int) $dateTime->format('H');
        if ($data['date'] !== $dateTime->format('Y-m-d ') . $normalizedHour . $dateTime->format(':i')) {
            throw new \InvalidArgumentException("Debe seleccionar una fecha.");
        }

        if (empty($data['duration'])) {
            throw new \InvalidArgumentException("Internal error.");
        }

        return true;
    }
}