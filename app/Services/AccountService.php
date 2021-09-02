<?php

namespace App\Services;

use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\IndexAccountRequest;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Contracts\AccountServiceInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Arr;

class AccountService implements AccountServiceInterface
{
    private ClientService $clientService;
    private DatabaseManager $manager;
    private UserRepositoryInterface $userRepository;
    private ClientRepositoryInterface $clientRepository;

    public function __construct(
        ClientService $clientService,
        UserRepositoryInterface $userRepository,
        DatabaseManager $manager,
        ClientRepositoryInterface $clientRepository
    ) {
        $this->clientService = $clientService;
        $this->manager = $manager;
        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
    }

    public function register(CreateAccountRequest $request): bool
    {
        $this->manager->beginTransaction();

        $client = $this->clientService->create($request);

        $this->userRepository->create($request, $client);

        $this->manager->commit();

        return true;
    }

    public function index(IndexAccountRequest $request): Paginator
    {
        $clients = $this->clientRepository->index($request);
        $details = $this->userRepository->getDetails($clients->collect()->pluck('id')->toArray());

        foreach ($clients as $client) {
            $client->details = Arr::get($details, $client->id, []);
        }

        return $clients;
    }
}
