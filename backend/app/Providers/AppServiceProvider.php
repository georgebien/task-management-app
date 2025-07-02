<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $path = database_path('database.sqlite');

        if (!file_exists($path)) {
            file_put_contents($path, '');
        }
    }
}
