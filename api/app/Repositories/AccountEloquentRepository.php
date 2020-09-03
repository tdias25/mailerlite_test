<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Contracts\AccountRepositoryContract;

class AccountEloquentRepository implements AccountRepositoryContract
{
    public function retrieveById($id)
    {
        return Account::findOrFail($id);
    }

    public function retrieveAll()
    {
        return Account::paginate(35);
    }

    public function update($id, array $array)
    {
        return Account::findOrFail($id)
            ->update($array);
    }

    public function create(array $data)
    {
        return Account::create($data);
    }
}
