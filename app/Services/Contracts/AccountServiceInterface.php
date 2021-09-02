<?php

namespace App\Services\Contracts;

use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\IndexAccountRequest;
use Illuminate\Contracts\Pagination\Paginator;

interface AccountServiceInterface
{
    public function register(CreateAccountRequest $request): bool;

    public function index(IndexAccountRequest $request): Paginator;
}
