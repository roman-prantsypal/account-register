<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property string $client_name
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $country
 * @property float $latitude
 * @property float $longitude
 * @property string $phone_no1
 * @property string $phone_no2
 * @property string $zip
 * @property string $status
 * @property Carbon $start_validity
 * @property Carbon $end_validity
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property array|null $details
 */
class Client extends Model
{
    protected $casts = [
        'start_validity' => 'datetime',
        'end_validity' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $fillable = [
        'client_name',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'zip',
        'phone_no1',
        'phone_no2',
        'start_validity',
        'end_validity',
        'status',
        'latitude',
        'longitude',
    ];
}
