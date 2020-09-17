<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{IUserService, IWalletService, IRate, ITransactionService};
use App\Services\{UserService, WalletService, TransactionService};
use App\Helpers\Rate;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IUserService::class, UserService::class);
        $this->app->singleton(IWalletService::class, WalletService::class);
        $this->app->singleton(IRate::class, Rate::class);
        $this->app->singleton(ITransactionService::class, TransactionService::class);
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
