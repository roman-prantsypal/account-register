<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\Contracts\CreateClientModel;
use App\Http\Requests\Contracts\IndexClientModel;
use App\Models\Client;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

interface ClientRepositoryInterface
{
    public function firstOrCreate(CreateClientModel $model, array $coordinate): Client;

    public function index(IndexClientModel $model): Paginator;

    public function query(): Builder;
}
