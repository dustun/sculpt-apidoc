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
        if (!config('sculpt-apidoc.enabled', true)) {
            return;
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/sculpt-apidoc.php', 'sculpt-apidoc');

        $this->publishes([
            __DIR__ . '/../config/sculpt-apidoc.php' => config_path('sculpt-apidoc.php'),
        ], 'sculpt-apidoc-config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/api-doc.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'sculpt');
    }
}