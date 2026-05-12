<?php

namespace DataSDK\Categories;

use Illuminate\Support\ServiceProvider;

class CategoriesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/categories.php' => config_path('categories.php'),
            ], 'config');
        }
    }
}
