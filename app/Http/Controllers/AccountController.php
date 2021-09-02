<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\IndexAccountRequest;
use App\Http\Resources\IndexAccountResponse;
use App\Services\Contracts\AccountServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller;

class AccountController extends Controller
{
    private AccountServiceInterface $accountService;

    public function __construct(AccountServiceInterface $accountService)
    {
        $this->accountService = $accountService;
    }

    public function register(CreateAccountRequest $request): JsonResponse
    {
        $this->accountService->register($request);

        return response()->json([], 201);
    }

    public function index(IndexAccountRequest $request): JsonResource
    {
        return new IndexAccountResponse(
            $this->accountService->index($request)
        );
    }
}
