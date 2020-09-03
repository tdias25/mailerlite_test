<?php

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            'name' => 'John',
            'balance' => 1500,
            'currency' => 'usd',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('accounts')->insert([
            'name' => 'Peter',
            'balance' => 1000,
            'currency' => 'eur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
