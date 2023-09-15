<?php

namespace App\Service;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\State;
use Doctrine\ORM\EntityManagerInterface;
use Geocoder\Query\GeocodeQuery;
use Geocoder\StatefulGeocoder;
use maxh\Nominatim\Nominatim;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GeolocalizationService
{
    private EntityManagerInterface $entityManager;

    private string $countryIso2;

    private Nominatim $nominatim;

    public function __construct(ParameterBagInterface $param, EntityManagerInterface $entityManager)
    {
        $this->countryIso2 = $_ENV['COUNTRY_CODE'];
        $this->entityManager = $entityManager;
        $this->nominatim = new Nominatim($_ENV['NOMINATIM_API_BASE_URL']);
    }

    public function geolocalize(string $address, int $number, int $cityId = null, int $stateId = null, int $postalCode = null): array
    {
        $entityManager = $this->entityManager;
        $countryRepository = $entityManager->getRepository(Country::class);

        $country = $countryRepository->findOneBy(['iso3' => $this->countryIso2]);

        // Construye los parámetros de la consulta
        $search = $this->nominatim->newSearch();
        $search->country($country->getName())
                ->street($address . ' ' . $number)
                ->polygon('geojson')
                ->addressDetails();

        if (!is_null($stateId)) {
            $stateRepository = $entityManager->getRepository(State::class);
            $state = $stateRepository->findOneBy(['id' => $stateId]);
            $search->state($state->getName());
        }

        if (!is_null($cityId)) {
            $cityRepository = $entityManager->getRepository(City::class);
            $city = $cityRepository->findOneBy(['id' => $cityId]);
            $search->state($city->getName());
        }

        // Agrega el código postal si está presente
        if (!is_null($postalCode)) {
            $search->postalCode($postalCode);
        }

        $results = $this->nominatim->find($search);


        return $this->nominatimGetBestLocalization($results, $postalCode);
    }

    private function nominatimGetBestLocalization(array $results, $postalCode) : array
    {
        $response = [];

        foreach ($results as $result) {
            if (str_contains($result["address"]["postcode"], $postalCode)) {
                $response = [
                    'address' => $result["address"]["road"],
                    'number' => $result["address"]["house_number"],
                    'longitud' => $result["lon"],
                    'latitud' => $result["lat"]
                ];
            }
        }

        return $response;
    }
}