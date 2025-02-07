<?php

namespace LaraProj\Setup\Providers;

use Illuminate\Support\ServiceProvider;

class LaraProjServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        // Publishing public assets (CSS, JS, etc.) to public/assets
        $this->publishes([
            __DIR__ . '/../Public/layouts' => public_path('layouts/'),
        ], 'setup-assets');

        $this->publishes([
            __DIR__ . '/../Resources/views/layouts' => resource_path('views/layouts/'),
        ], 'setup-layouts');

        $this->publishes([
            __DIR__ . '/../Resources/views/auth' => resource_path('views/auth/'),
        ], 'setup-auth');

        $this->publishes([
            __DIR__ . '/../Resources/views/example' => resource_path('views/example/'),
            __DIR__ . '/../App/Http/Controllers/ExampleController.php' => app_path('Http/Controllers/ExampleController.php'),
            __DIR__ . '/../App/Models/Example.php' => app_path('Models/Example.php'),
            __DIR__ . '/../App/Models/Example.php' => app_path('Models/Example.php'),
            __DIR__ . '/../Database/Migrations' => database_path('migrations'),
            __DIR__ . '/../Database/Seeders' => database_path('seeders'),
            __DIR__ . '/../App/Traits/FilterableTrait.php' => app_path('Traits/FilterableTrait.php'),
            __DIR__ . '/../App/Http/Requests/ExampleRequest.php' => app_path('Http/Requests/ExampleRequest.php'),
            __DIR__ . '/../Routes/web.php' => base_path('routes/web.php'),
        ], 'setup-crud');

        $this->publishes([], 'setup-controller');

        $this->publishes([], 'setup-model');

        $this->publishes([], 'setup-trait');

        $this->publishes([], 'setup-request');

        $this->publishes([], 'setup-routes');

        //php artisan vendor:publish --provider="LaraProj\Setup\Providers\LaraProjServiceProviderServiceProvider"
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
