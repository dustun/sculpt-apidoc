<?php

declare(strict_types=1);

namespace Sculpt\ApiDoc\Extractors;

use Illuminate\Routing\Route;
use ReflectionMethod;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required as RequiredAttribute;

final class DtoExtractor
{
    public function extract(ReflectionMethod $method, Route $route): array
    {
        $bodySchema = null;

        foreach ($method->getParameters() as $parameter) {
            $type = $parameter->getType()?->getName();

            if ($type && is_subclass_of($type, Data::class)) {
                $bodySchema = $this->extractFromDataClass($type);
                break;
            }
        }

        return [
            'summary'     => $this->getSummary($method),
            'description' => $this->getDescription($method),
            'tags'        => [$this->getTagFromRoute($route)],
            'requestBody' => $bodySchema ? [
                'required' => true,
                'content' => [
                    'application/json' => [
                        'schema' => $bodySchema
                    ]
                ]
            ] : null,
            'responses' => [
                '200' => ['description' => 'Successful response'],
                '201' => ['description' => 'Resource created'],
                '422' => ['description' => 'Validation failed'],
            ],
        ];
    }

    private function extractFromDataClass(string $dataClass): array
    {
        $reflection = new \ReflectionClass($dataClass);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return ['type' => 'object', 'properties' => []];
        }

        $properties = [];
        $required = [];

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType()?->getName() ?? 'string';

            $isRequired = $param->getAttributes(RequiredAttribute::class) || !$param->isOptional();

            $properties[$name] = [
                'type' => $this->mapPhpTypeToOpenApi($type),
            ];

            if ($isRequired) {
                $required[] = $name;
            }
        }

        $schema = [
            'type' => 'object',
            'properties' => $properties,
        ];

        if (!empty($required)) {
            $schema['required'] = $required;
        }

        return $schema;
    }

    private function mapPhpTypeToOpenApi(?string $type): string
    {
        return match ($type) {
            'int', 'integer' => 'integer',
            'float', 'double' => 'number',
            'bool', 'boolean' => 'boolean',
            default => 'string',
        };
    }

    private function getSummary(ReflectionMethod $method): string
    {
        $doc = $method->getDocComment();
        if ($doc && preg_match('/^\s*\*\s*(.+?)$/m', $doc, $m)) {
            return trim($m[1]);
        }
        return ucfirst($method->getName());
    }

    private function getDescription(ReflectionMethod $method): string
    {
        $doc = $method->getDocComment();
        if ($doc) {
            preg_match_all('/^\s*\*\s*(.+?)$/m', $doc, $matches);
            return implode("\n", array_slice($matches[1], 1));
        }
        return '';
    }

    private function getTagFromRoute(Route $route): string
    {
        $uri = $route->uri();
        if (str_contains($uri, 'auth')) return 'Auth';
        if (str_contains($uri, 'order')) return 'Orders';
        return 'General';
    }
}