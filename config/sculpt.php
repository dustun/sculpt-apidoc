<?php

return [
    'enabled' => env('SCULPT_APIDOC_ENABLED', true),

    'title'       => env('SCULPT_APIDOC_TITLE', 'Sculpt API Documentation'),
    'description' => env('SCULPT_APIDOC_DESCRIPTION', 'Powerful API documentation by Sculpt'),
    'version'     => env('SCULPT_APIDOC_VERSION', '1.0.0'),

    'route_prefix' => 'docs',

    'ui' => [
        'theme' => 'dark',
    ],
];