<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountsTest extends TestCase
{
    use RefreshDatabase;

    private $AccountRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->AccountRepository = $this->app->make(
            'App\Repositories\Contracts\AccountRepositoryContract'
        );
    }

    public function test_an_existing_account_can_be_retrieved()
    {
        $account = $this->AccountRepository->create([
            'name' => 'John Doe',
            'balance' => 15000,
            'currency' => 'usd'
        ]);

        $response = $this->get('/api/accounts/' . $account->id);

        $response->assertJson(
            [
                'result' => [
                    'name' => $account->name,
                    'balance' => $account->balance,
                    'currency' => $account->currency
                ]
            ]
        );
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_non_existing_account_canot_be_retrieved()
    {
        $response = $this->get('/api/accounts/nan999');

        $response->assertJson([
            'success' => 'false'
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
