<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\Contracts\AccountRepositoryContract',
            'App\Repositories\AccountEloquentRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\TransactionRepositoryContract',
            'App\Repositories\TransactionEloquentRepository'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
