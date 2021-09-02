<?php

namespace App\Repositories;

use App\Enums\Status;
use App\Http\Requests\Contracts\CreateUserModel;
use App\Models\Client;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function create(CreateUserModel $model, Client $client): User
    {
        $user = new User();

        $user->first_name = $model->getFirstName();
        $user->last_name = $model->getLastName();
        $user->email = $model->getEmail();
        $user->password = $model->getPassword();
        $user->phone = $model->getPhone();
        $user->status = Status::ACTIVE;
        $user->last_password_reset = Carbon::now();
        $user->client()->associate($client);
        $user->save();

        return $user;
    }

    public function getDetails(array $ids): array
    {
        $data = $this->query()->whereIn('client_id', [19])
            ->groupBy(['client_id', 'status'])
            ->select(DB::raw('DISTINCT client_id, count(*) as count, status'))
            ->get()
            ->toArray();

        $details = [];

        foreach ($data as $params) {
            $key = Arr::get($params, 'client_id');
            $details[Arr::get($params, 'client_id')] = array_merge(
                Arr::get($details, $key, []),
                [
                    $params['status'] => $params['count'],
                ]
            );
        }

        return $details;
    }

    public function query(): Builder
    {
        return User::query();
    }
}
