<?php

namespace App\Repositories;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryContract;

class TransactionEloquentRepository implements TransactionRepositoryContract
{
    public function retrieveById($id)
    {
        return Transaction::find($id);
    }

    public function retrieveAllByAccountId($id)
    {
        return Transaction::where('to', $id)
            ->orWhere('from', $id)
            ->paginate(35);
    }

    public function create(array $data)
    {
        return Transaction::create([
            'to' => $data['to'],
            'from' => $data['from'],
            'amount' => $data['amount'],
            'details' => $data['details']
        ]);
    }
}
