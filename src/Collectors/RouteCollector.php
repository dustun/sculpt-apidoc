<?php

declare(strict_types=1);

namespace Sculpt\ApiDoc\Collectors;

use Illuminate\Support\Facades\Route;
use Sculpt\ApiDoc\Contracts\CollectorInterface;
use Sculpt\ApiDoc\Extractors\DtoExtractor;

final class RouteCollector implements CollectorInterface
{
    private DtoExtractor $dtoExtractor;

    public function __construct()
    {
        $this->dtoExtractor = new DtoExtractor();
    }

    public function collect(): array
    {
        $paths = [];

        foreach (Route::getRoutes() as $route) {
            // Документируем только API маршруты
            if (!str_starts_with($route->uri(), 'api/')) {
                continue;
            }

            $action = $route->getAction('controller');
            if (!$action || !class_exists($action)) {
                continue;
            }

            $controller = new \ReflectionClass($action);
            $method = $controller->getMethod('__invoke');

            $path = '/' . ltrim($route->uri(), '/');
            $httpMethod = strtolower($route->methods()[0]);

            $paths[$path][$httpMethod] = $this->dtoExtractor->extract($method, $route);
        }

        return ['paths' => $paths];
    }
}