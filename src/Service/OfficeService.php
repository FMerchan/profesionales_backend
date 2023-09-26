<?php

namespace App\Service;

use App\Entity\City;
use App\Entity\Office;
use App\Entity\State;
use App\Entity\Turn;
use App\Entity\UserProfessional;
use Doctrine\ORM\EntityManagerInterface;

class OfficeService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createOffice(UserProfessional $userProfessional, array $data, ?int $officeId = null): Office
    {
        // Validar los datos antes de continuar
        $this->validateData($data);

        // Crear una nueva instancia de la entidad Office
        if (is_null($officeId)) {
            $office = new Office();
        } else {
            $entityManager = $this->entityManager;
            $office = $entityManager->getRepository(Office::class)->find($officeId);
        }

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
                throw new \InvalidArgumentException("La ciudad con ID " . $data['localization']['city_id'] . " no existe.");
            }
            $office->setCity($city);
        }

        // Obtener y asociar la entidad State
        if (isset($data['localization']['state_id'])) {
            $state = $this->entityManager->getRepository(State::class)->find($data['localization']['state_id']);
            if (!$state) {
                throw new \InvalidArgumentException("El estado con ID " . $data['localization']['state_id'] . " no existe.");
            }

            $office->setState($state);
        }

        // Persistir en la base de datos
        if (is_null($officeId)) {
            $office->setUserProfessional($userProfessional);
            $this->entityManager->persist($office);
        }
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

    public function calculateAvailableDatesAndTimes(int $officeId): array
    {
        // Obtener la información de la oficina
        $office = $this->entityManager->getRepository(Office::class)->find($officeId);

        // Obtener los tiempos disponibles para turnos de la oficina
        $availableTimes = $office->getAvailableTimes();
        $businessDays = $office->getBusinessDays();

        // Calcular las fechas y horarios disponibles
        $availableDatesAndTimes = [];

        $currentDate = new \DateTime();
        $currentDate->modify('+1 days');
        $endDate = new \DateTime();
        $endDate->modify('+30 days');

        while ($currentDate <= $endDate) {
            $currentDayOfWeek = $currentDate->format('w');
            // Verificar si el día actual está en el array de días hábiles
            if (in_array(Office::DAY_AVAILABLE_MAPPING[$currentDayOfWeek], $businessDays) !== false) {
                // Obtener los turnos existentes para la oficina
                $existingTurns = $this->entityManager->getRepository(Turn::class)->findByOfficeIdAndDates($officeId, $currentDate->format('Y-m-d 00:00:00'), $currentDate->format('Y-m-d 23:59:59'));

                $availableDatesAndTimes[$currentDate->format('Y-m-d')] = [];

                foreach ($availableTimes as $timeSlot) {
                    $from = strtotime( $currentDate->format('Y-m-d') .' '. $timeSlot['from']);
                    $until = strtotime( $currentDate->format('Y-m-d') .' '. $timeSlot['until']);

                    if ($from === false || $until === false || $from >= $until) {
                        // Validación de tiempos inválidos
                        continue;
                    }

                    // Generar las fechas y horarios dentro del intervalo
                    $currentTime = $from;
                    while ($currentTime < $until) {
                        $endTime = $currentTime + ($office->getDuration() * 60); // Duración del turno en segundos

                        // Verificar si el horario actual está disponible
                        if (!$this->isTimeSlotBooked($existingTurns, $currentTime, $endTime)) {
                            $availableDatesAndTimes[$currentDate->format('Y-m-d')][] =  date('H:i', $currentTime);
                            //$availableDatesAndTimes[] = date('Y-m-d H:i:s', $currentTime);
                        }

                        $currentTime += ($office->getDuration() * 60);
                    }
                }
            }

            $currentDate->modify('+1 day');
        }

        return $availableDatesAndTimes;
    }

    private function isTimeSlotBooked(array $existingTurns, int $startTime, int $endTime): bool
    {
        // Verificar si hay un turno existente que se superponga con el horario dado
        foreach ($existingTurns as $turn) {
            $turnStartTime = strtotime($turn->getDate()->format('Y-m-d H:i:s'));
            $turnEndTime = $turnStartTime + ($turn->getDuration() * 60);

            if ($startTime >= $turnStartTime && $startTime < $turnEndTime) {
                return true;
            }
        }

        return false;
    }
}