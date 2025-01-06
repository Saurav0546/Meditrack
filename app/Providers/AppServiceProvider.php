<?php

namespace App\Providers;
use App\Models\Medicine;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

/*************  ✨ Codeium Command 🌟  *************/
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('PriceRange', function ($value) {
            return Medicine::withoutGlobalScope('active')->where('PriceRange', $value)->firstOrFail();
        });
    }
}