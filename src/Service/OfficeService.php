<?php

namespace App\Service;

use App\Entity\City;
use App\Entity\Office;
use App\Entity\State;
use App\Entity\UserProfessional;
use Doctrine\ORM\EntityManagerInterface;

class OfficeService
{
    private EntityManagerInterface $entityManager;
    private OpenStreetMapGeocoderService $geocoderService;

    public function __construct(EntityManagerInterface $entityManager, OpenStreetMapGeocoderService $geocoderService)
    {
        $this->entityManager = $entityManager;
        $this->geocoderService = $geocoderService;
    }

    public function createOffice(UserProfessional $userProfessional, array $data, ?string $userIP): Office
    {
        // Validar los datos antes de continuar
        $this->validateData($data);

        // Crear una nueva instancia de la entidad Office
        $office = new Office();

        $office->setName($data['name'] ?? null);
        $office->setDetail($data['detail'] ?? null);
        $office->setDuration($data['duration'] ?? null);
        $office->setPrice($data['price'] ?? null);
        $office->setBusinessDays($data['business_days'] ?? null);
        $office->setAvailableTimes($data['available_times'] ?? null);

        $office->setAddress($data['localization']['address'] . ' ' . $data['localization']['number']  ?? null);
        $office->setPostalCode($data['localization']['postal_code'] ?? null);
        $office->setLongitude($data['localization']['coordinates']['longitude'] ?? null);
        $office->setLatitude($data['localization']['coordinates']['latitude'] ?? null);

        // Obtener y asociar la entidad City
        if (isset($data['localization']['city_id'])) {
            $city = $this->entityManager->getRepository(City::class)->find($data['localization']['city_id']);
            if (!$city) {
                throw new \InvalidArgumentException("La ciudad con ID $cityId no existe.");
            }
            $office->setCity($city);
        }

        // Obtener y asociar la entidad State
        if (isset($data['localization']['state_id'])) {
            $state = $this->entityManager->getRepository(State::class)->find($data['localization']['state_id']);
            if (!$state) {
                throw new \InvalidArgumentException("El estado con ID $stateId no existe.");
            }

            $office->setState($state);
        }

        $office->setUserProfessional($userProfessional);

        // Persistir en la base de datos
        $this->entityManager->persist($office);
        $this->entityManager->flush();

        return $office;
    }

    // Validación de los datos antes de guardar
    private function validateData(array $data): bool
    {
        // Validar la existencia de al menos una franja horaria
        if (empty($data['available_times'])) {
            throw new \InvalidArgumentException("Debe haber al menos una franja horaria.");
        }

        // Validar la estructura y coherencia de las franjas horarias
        $timeSlots = $data['available_times'];
        $usedTimeSlots = [];

        // Validar la estructura y coherencia de las franjas horarias
        foreach ($timeSlots as $timeSlot) {
            if (!isset($timeSlot['from'], $timeSlot['until'])) {
                throw new \InvalidArgumentException("Las franjas horarias estan incompletas");
            }

            $fromTime = strtotime($timeSlot['from']);
            $untilTime = strtotime($timeSlot['until']);

            if ($fromTime === false || $untilTime === false || $fromTime >= $untilTime) {
                throw new \InvalidArgumentException("Las franjas horarias deben tener horas válidas.");
            }

            // Validar superposición de franjas horarias
            foreach ($usedTimeSlots as $usedTimeSlot) {
                $usedFromTime = strtotime($usedTimeSlot['from']);
                $usedUntilTime = strtotime($usedTimeSlot['until']);

                if (($fromTime >= $usedFromTime && $fromTime <= $usedUntilTime) ||
                    ($untilTime >= $usedFromTime && $untilTime <= $usedUntilTime)) {
                    throw new \InvalidArgumentException("Las franjas horarias no deben superponerse.");
                }
            }

            // Registrar la franja horaria utilizada
            $usedTimeSlots[] = $timeSlot;
        }

        return true;
    }
}