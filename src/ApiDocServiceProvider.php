<?php

declare(strict_types=1);

namespace Sculpt\ApiDoc;

use Illuminate\Support\ServiceProvider;

class ApiDocServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ApiDocService::class);
    }

    public function boot(): void
    {
        if (!config('sculpt.enabled', true)) {
            return;
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/sculpt.php', 'sculpt');

        $this->publishes([
            __DIR__ . '/../config/sculpt.php' => config_path('sculpt.php'),
        ], 'sculpt-config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/api-doc.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'sculpt');
    }
}