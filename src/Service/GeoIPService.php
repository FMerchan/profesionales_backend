<?php

namespace App\Service;

use GeoIp2\Database\Reader;

class GeoIPService
{
    private Reader $reader;

    public function __construct(?string $databasePath)
    {
        //$this->reader = new Reader($databasePath);
    }

    public function getLocationByIP(string $ip): ?array
    {
        try {
            $record = $this->reader->city($ip);

            return [
                'city' => $record->city->name,
                'country' => $record->country->name,
                'latitude' => $record->location->latitude,
                'longitude' => $record->location->longitude,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
}