<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoogleMapsGeocoderService
{
    private ParameterBagInterface $params;
    private string $apiKey;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->apiKey = $this->params->get('google_maps_api_key');
    }

    public function getCoordinates(string $address): ?array
    {
        $client = new Client();

        $response = $client->get($this->params->get('google_maps_api_base_url'), [
            'query' => [
                'address' => $address,
                'key' => $this->apiKey,
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['results'][0]['geometry']['location'])) {
            return $data['results'][0]['geometry']['location'];
        }

        return null;
    }
}