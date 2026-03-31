<?php

declare(strict_types=1);

namespace Sculpt\ApiDoc;

use Sculpt\ApiDoc\Collectors\RouteCollector;

final class ApiDocService
{
    public function generate(): array
    {
        $collector = new RouteCollector();

        $spec = [
            'openapi' => '3.1.0',
            'info' => [
                'title'       => config('sculpt-apidoc.title', 'Sculpt API Documentation'),
                'description' => config('sculpt-apidoc.description', 'Powerful API documentation generator for Laravel'),
                'version'     => config('sculpt-apidoc.version', '1.0.0'),
            ],
            'servers' => [
                ['url' => config('app.url')],
            ],
            'paths' => $collector->collect()['paths'] ?? [],
        ];

        return $spec;
    }
}