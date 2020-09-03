<?php

namespace Tests\Feature;

use App\Models\Account;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private $TransactionRepository;
    private $AccountRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->TransactionRepository = $this->app->make(
            'App\Repositories\Contracts\TransactionRepositoryContract'
        );
    }

    public function test_fields_are_required_when_creating_a_transaction()
    {
        $response = $this->post('/api/accounts/1/transactions', [
            'to' => '',
            'amount' => '',
            'details' => ''
        ]);

        $response->assertSessionHasErrors('amount');
        $response->assertSessionHasErrors('to');
        $response->assertSessionHasErrors('details');
    }

    public function test_transactions_can_be_retrieved()
    {
        $AccountRepository = $this->app->make(
            'App\Repositories\Contracts\AccountRepositoryContract'
        );

        $account1 = $AccountRepository->create([
            'name' => 'John Doe',
            'balance' => 15000,
            'currency' => 'usd'
        ]);

        $account2 = $AccountRepository->create([
            'name' => 'Izabella Doe',
            'balance' => 100,
            'currency' => 'usd'
        ]);

        $transactionAmount = 300;
        $transactionDetails = 'test transaction';

        $transaction = $this->TransactionRepository->create([
            'from' => $account1->id,
            'to' => $account2->id,
            'amount' => $transactionAmount,
            'details' => $transactionDetails
        ]);

        $response = $this->get('/api/accounts/' . $account1->id . '/transactions');

        $expected = [];
        $expected['result'] = [
            'data' => [
                0 => [
                    'id' => $transaction->id,
                    'from' => $account1->id,
                    'to' => $account2->id,
                    'details' => $transactionDetails,
                    'amount' => $transactionAmount
                ]
            ]
        ];

        $response->assertJson($expected);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_a_account_without_enough_balance_cant_create_a_transaction()
    {
        $AccountRepository = $this->app->make(
            'App\Repositories\Contracts\AccountRepositoryContract'
        );

        $account1 = $AccountRepository->create([
            'name' => 'John Doe',
            'balance' => 0,
            'currency' => 'usd'
        ]);

        $account2 = $AccountRepository->create([
            'name' => 'Izabella Doe',
            'balance' => 100,
            'currency' => 'usd'
        ]);

        $transactionAmount = 300;
        $transactionDetails = 'test transaction';

        $response = $this->post('/api/accounts/' . $account1->id . '/transactions', [
            'to' => $account2->id,
            'amount' => $transactionAmount,
            'details' => $transactionDetails
        ]);

        $expected = [
            'success' => 'false',
            'errors' => [
                'source account does not have enough balance',
            ]
        ];

        $response->assertJson($expected);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_a_transaction_cannot_be_created_to_the_same_account()
    {
        $AccountRepository = $this->app->make(
            'App\Repositories\Contracts\AccountRepositoryContract'
        );

        $account1 = $AccountRepository->create([
            'name' => 'John Doe',
            'balance' => 350,
            'currency' => 'usd'
        ]);


        $transactionAmount = 300;
        $transactionDetails = 'test transaction';

        $response = $this->post('/api/accounts/' . $account1->id . '/transactions', [
            'to' => $account1->id,
            'amount' => $transactionAmount,
            'details' => $transactionDetails
        ]);

        $expected = [
            'success' => 'false',
            'errors' => ['a transaction cannot be created to the same account']
        ];

        $response->assertJson($expected);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_a_transaction_cannot_be_created_to_an_unexisting_account()
    {
        $AccountRepository = $this->app->make(
            'App\Repositories\Contracts\AccountRepositoryContract'
        );

        $account1 = $AccountRepository->create([
            'name' => 'John Doe',
            'balance' => 350,
            'currency' => 'usd'
        ]);

        $transactionAmount = 300;
        $transactionDetails = 'test transaction';

        $response = $this->post('/api/accounts/' . $account1->id . '/transactions', [
            'to' => '9a2',
            'amount' => $transactionAmount,
            'details' => $transactionDetails
        ]);

        $response->assertJson([
            'success' => 'false'
        ]);

    }
}
