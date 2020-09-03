<?php

namespace App\Http\Controllers;

use App\Http\Requests\Requests\AccountRequest;
use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    public function index(AccountRepositoryContract $AccountRepository)
    {
        try {

            $accounts = $AccountRepository->retrieveAll();

            return response()->json([
                'success' => 'true',
                'result' => $accounts
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => 'false',
                'errors' => [
                    $e->getMessage()
                ]

            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function show($id, AccountRepositoryContract $AccountRepository)
    {
        try {

            $account = $AccountRepository->retrieveById($id);

            return response()->json([
                'success' => 'true',
                'result' => $account
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => 'false',
                'errors' => [
                    $e->getMessage()
                ]

            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function transactions($id, AccountRepositoryContract $AccountRepository, TransactionRepositoryContract $TransactionRepository)
    {
        try {

            $account = $AccountRepository->retrieveById($id);
            $transactions = $TransactionRepository->retrieveAllByAccountId($account->id);

            return response()->json([
                'success' => 'true',
                'result' => $transactions
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => 'false',
                'errors' => [
                    $e->getMessage()
                ]
            ], Response::HTTP_NOT_FOUND);
        }
    }

}
