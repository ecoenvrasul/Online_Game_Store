<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Employee;
use App\Policies\AuthPolicy;
use App\Observers\UserObserver;
use App\Policies\ProductPolicy;
use App\Observers\OrderObserver;
use App\Policies\EmployeePolicy;
use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [

    ];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::unguard();
        Product::observe(ProductObserver::class);
        Order::observe(OrderObserver::class);
        User::observe(UserObserver::class);
    }
}
