<?php

namespace App\Repositories;

use App\Enums\Status;
use App\Http\Requests\Contracts\CreateClientModel;
use App\Http\Requests\Contracts\IndexClientModel;
use App\Models\Client;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class ClientRepository implements ClientRepositoryInterface
{
    protected const FIELD_ALIAS = [
        'zipCode' => 'zip',
        'phoneNo1' => 'phone_no1',
        'phoneNo2' => 'phone_no2',
        'startValidity' => 'start_validity',
        'endValidity' => 'end_validity',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public function firstOrCreate(CreateClientModel $model, array $coordinate): Client
    {
        $startValidity = new Carbon();

        return Client::firstOrCreate(
            [
                'client_name' => $model->getName(),
                'address1' => $model->getAddress1(),
                'address2' => $model->getAddress2(),
                'city' => $model->getCity(),
                'state' => $model->getState(),
                'country' => $model->getCountry(),
                'zip' => $model->getZipCode(),
                'phone_no1' => $model->getPhoneNo1(),
                'phone_no2' => $model->getPhoneNo2(),
            ],
            [
                'start_validity' => $startValidity,
                'end_validity' => (clone $startValidity)->addDays(15),
                'status' => Status::ACTIVE,
                'latitude' => Arr::get($coordinate, 'lat'),
                'longitude' => Arr::get($coordinate, 'lng'),
            ]
        );
    }

    public function index(IndexClientModel $model): Paginator
    {
        $query = $this->query();

        $whereLike = array_filter(
            [
                'name' => $model->getName(),
                'address1' => $model->getAddress1(),
                'address2' => $model->getAddress2(),
                'city' => $model->getCity(),
                'state' => $model->getState(),
                'country' => $model->getCountry(),
                'zip' => $model->getZipCode(),
                'phone_no1' => $model->getPhoneNo1(),
                'phone_no2' => $model->getPhoneNo2(),
                'id' => $model->getId(),
                'latitude' => $model->getLatitude(),
                'longitude' => $model->getLongitude(),
                'status' => $model->getStatus(),
            ]
        );

        $whereDate = array_filter(
            [
                'start_validity' => $model->getStartValidity(),
                'end_validity' => $model->getEndValidity(),
                'created_at' => $model->getCreatedAt(),
                'updated_at' => $model->getUpdateAt(),
            ]
        );

        foreach ($whereLike as $field => $value) {
            $query->where($field, 'LIKE', sprintf('%%%s%%', $value));
        }

        foreach ($whereDate as $field => $value) {
            $query->whereDate($field, $value);
        }

        return $query
            ->orderBy(Arr::get(static::FIELD_ALIAS, $model->getSort(), $model->getSort()), $model->getOrder())
            ->paginate();
    }

    public function query(): Builder
    {
        return Client::query();
    }
}
