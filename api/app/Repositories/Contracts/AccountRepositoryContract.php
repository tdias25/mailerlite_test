<?php

namespace App\Repositories\Contracts;

interface AccountRepositoryContract
{
    public function retrieveById($id);
    public function retrieveAll();
    public function create(array $data);
    public function update($id, array $data);
}
