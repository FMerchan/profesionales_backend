<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\State;
use App\Service\GeolocalizationService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Provider\OpenCage\OpenCage;

/**
 * @Route("/localization")
 */
class GeolocalizationController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @Route("/geolocalize", name="geolocalize", methods={"POST"})
     */
    public function geolocalize(
        Request $request,
        GeolocalizationService $geolocalizationService
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);

            // Recoge los componentes de la dirección
            $street = (isset($data['street'])) ? $data['street'] : null;
            $number = (isset($data['number'])) ? $data['number'] : null;
            $cityId  = (isset($data['cityId'])) ? $data['cityId'] : null;
            $stateId = (isset($data['stateId'])) ? $data['stateId'] : null;
            $postalCode = (isset($data['postalCode'])) ? $data['postalCode'] : null;

            // Llama al servicio para realizar la geolocalización
            $result = $geolocalizationService->geolocalize($street, $number, $cityId, $stateId, $postalCode);

            if ($result) {
                return new JsonResponse(['status' => true, 'data' => $result]);
            }

            return new JsonResponse(['status' => false, 'message' => 'No se encontró una coincidencia adecuada']);
        } catch (\Exception $e) {
            // Manejo de errores
            return new JsonResponse(['status' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * @Route("/states", name="states", methods={"GET"})
     */
    public function states(): JsonResponse {
        $country = $this->entityManager->getRepository(Country::class)->findOneBy(['iso3' => $_ENV['COUNTRY_CODE']]);
        $sates = $this->entityManager->getRepository(State::class)->findBy(['country' => $country->getId()], ['name' => 'ASC']);

        // Convert the professionals array to a format suitable for JSON response
        $responseArray = [];
        foreach ($sates as $sate) {
            $responseArray[] = [
                'id' => $sate->getId(),
                'name' => $sate->getName(),
            ];
        }

        return $this->json($responseArray);
    }

    /**
     * @Route("/cities/{stateId}", name="cities", methods={"GET"})
     */
    public function cities(int $stateId): JsonResponse {
        $cities = $this->entityManager->getRepository(City::class)->findBy(['state' => $stateId], ['name' => 'ASC']);

        $responseArray = [];
        foreach ($cities as $city) {
            $responseArray[] = [
                'id' => $city->getId(),
                'name' => $city->getName(),
            ];
        }

        return $this->json($responseArray);
    }
}
