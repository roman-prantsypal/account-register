<?php

namespace App\Services;

use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;
use Spatie\Geocoder\Geocoder;

class GeoCoordinateService
{
    private Geocoder $geocoder;
    private RedisManager $manager;

    public function __construct(string $apiKey, Geocoder $geocoder, RedisManager $manager)
    {
        $this->geocoder = $geocoder->setApiKey($apiKey);
        $this->manager = $manager;
    }

    public function getCoordinates(string $address): array
    {
        $connection = $this->manager->connection();

        if ($coordinates = $connection->get($address)) {
            return json_decode($coordinates, true);
        }

        $coordinates = Arr::only(
            $this->geocoder->getCoordinatesForAddress($address),
            ['lat', 'lng',]
        );

        $connection->set($address, json_encode($coordinates));

        return $coordinates;
    }
}
