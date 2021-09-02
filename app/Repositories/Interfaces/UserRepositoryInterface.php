<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\Contracts\CreateUserModel;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

interface UserRepositoryInterface
{
    public function create(CreateUserModel $model, Client $client): User;

    public function getDetails(array $ids): array;

    public function query(): Builder;
}
