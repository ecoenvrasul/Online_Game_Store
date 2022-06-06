<?php

namespace App\Providers;

use App\Models\News;
use App\Models\Genre;
use App\Models\Basket;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Developer;
use App\Models\Promocode;
use App\Policies\NewsPolicy;
use App\Policies\GenrePolicy;
use App\Policies\BasketPolicy;
use App\Policies\ProductPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\DeveloperPolicy;
use App\Policies\PromocodePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Employee::class => EmployeePolicy::class,
        Basket::class => BasketPolicy::class,
        Developer::class => DeveloperPolicy::class,
        Genre::class => GenrePolicy::class,
        News::class => NewsPolicy::class,
        Promocode::class => PromocodePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
