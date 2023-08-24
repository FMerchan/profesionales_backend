<?php

namespace App\Service;

use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OpenStreetMapGeocoderService
{
    private ParameterBagInterface $params;
    private GeoIPService $geoIPService;

    public function __construct(ParameterBagInterface $params) // , GeoIPService $geoIPService
    {
        $this->params = $params;
        //$this->geoIPService = $geoIPService;
    }

    public function getCoordinatesByIPAndAddress(string $ip, string $address): ?array
    {
        // Obtener información de ciudad y país utilizando el servicio GeoIP
        $locationData = $this->geoIPService->getLocationByIP($ip);

        if ($locationData) {
            $city = $locationData['city'];
            $country = $locationData['country'];

            // Realizar consulta específica en Nominatim utilizando la dirección y la información de la ciudad y el país
            $client = new Client();

            $response = $client->get($this->params->get('nominatim_api_base_url'), [
                'query' => [
                    'format' => 'json',
                    'q' => $address,
                    'city' => $city,
                    'country' => $country,
                    'limit' => 1,
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            if (!empty($data)) {
                $firstResult = $data[0];

                return [
                    'city' => $city,
                    'country' => $country,
                    'latitude' => $firstResult['lat'],
                    'longitude' => $firstResult['lon'],
                ];
            }
        }

        return null;
    }

    public function getCoordinates(string $address): ?array
    {
        $client = new Client();

        $response = $client->get($this->params->get('nominatim_api_base_url'), [
            'query' => [
                'format' => 'json',
                'q' => $address,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if (!empty($data)) {
            return [
                'lat' => $data[0]['lat'],
                'lng' => $data[0]['lon'],
            ];
        }

        return null;
    }
}