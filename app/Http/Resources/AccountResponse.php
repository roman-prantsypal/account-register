<?php

namespace App\Http\Resources;

use App\Models\Client;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class AccountResponse extends JsonResource
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->client->id,
            'name' => $this->client->client_name,
            'address1' => $this->client->address1,
            'address2' => $this->client->address2,
            'city' => $this->client->city,
            'state' => $this->client->state,
            'country' => $this->client->country,
            'zipCode' => $this->client->zip,
            'latitude' => $this->client->latitude,
            'longitude' => $this->client->longitude,
            'phoneNo1' => $this->client->phone_no1,
            'phoneNo2' => $this->client->phone_no2,
            'totalUser' => $this->getDetails(),
            'startValidity' => $this->client->start_validity,
            'endValidity' => $this->client->end_validity,
            'status' => $this->client->status,
            'createdAt' => $this->client->created_at,
            'updateAt' => $this->client->updated_at,
        ];
    }

    public function getDetails(): array
    {
        if (!$this->client->details) {
            return [
                'all' => 0,
                'active' => 0,
                'inactive' => 0,
            ];
        }

        $active = Arr::get($this->client->details, 'Active', 0);
        $inactive = Arr::get($this->client->details, 'Inactive', 0);

        return [
            'all' => $active + $inactive,
            'active' => $active,
            'inactive' => $inactive,
        ];
    }
}
