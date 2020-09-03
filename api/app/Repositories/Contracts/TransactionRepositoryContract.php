<?php

namespace App\Repositories\Contracts;

interface TransactionRepositoryContract
{
    public function retrieveById($id);
    public function retrieveAllByAccountId($id);
    public function create(array $data);
}
