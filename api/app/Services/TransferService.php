<?php

namespace App\Services;

use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;

class TransferService
{
    private $AccountRepository;
    private $TransactionRepository;

    public function __construct(
        AccountRepositoryContract $AccountRepository,
        TransactionRepositoryContract $TransactionRepository
    )
    {
        $this->AccountRepository = $AccountRepository;
        $this->TransactionRepository = $TransactionRepository;
    }

    public function transfer(string $from, string $to, float $amount, string $details)
    {

        $fromAccount = $this->AccountRepository->retrieveById($from);
        $toAccount = $this->AccountRepository->retrieveById($to);

        if ($from == $to) {
            throw new \Exception('a transaction cannot be created to the same account');
        }

        if ($fromAccount->balance < $amount) {
            throw new \Exception('source account does not have enough balance');
        }

        //begin db transaction

        $this->TransactionRepository->create([
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
            'details' => $details
        ]);

        $this->AccountRepository->update($from, [
            'balance' => $fromAccount->balance - (float)$amount
        ]);

        $this->AccountRepository->update($to, [
            'balance' => $toAccount->balance + (float)$amount
        ]);

      //end db transaction
    }
}
