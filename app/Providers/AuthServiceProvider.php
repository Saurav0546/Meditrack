<?php

namespace App\Providers;

use App\Models\Medicine;
use Illuminate\Support\Facades\Gate;
use App\Models\Order;
use App\Policies\MedicinePolicy;
use App\Policies\OrderPolicy;


// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Medicine::class => MedicinePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

    }
}