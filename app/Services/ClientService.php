<?php

namespace App\Services;

use App\Http\Requests\Contracts\CreateClientModel;
use App\Models\Client;
use App\Repositories\Interfaces\ClientRepositoryInterface;

class ClientService
{
    private GeoCoordinateService $geoCoordinateService;
    private ClientRepositoryInterface $clientRepository;

    public function __construct(GeoCoordinateService $geoCoordinateService, ClientRepositoryInterface $clientRepository)
    {
        $this->geoCoordinateService = $geoCoordinateService;
        $this->clientRepository = $clientRepository;
    }

    public function create(CreateClientModel $model): Client
    {
        $coordinate = $this->geoCoordinateService->getCoordinates($model->getAddress1());

        return $this->clientRepository->firstOrCreate($model, $coordinate);
    }
}
