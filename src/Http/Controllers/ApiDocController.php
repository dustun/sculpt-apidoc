<?php

declare(strict_types=1);

namespace Sculpt\ApiDoc\Http\Controllers;

use Illuminate\Contracts\View\View;
use Sculpt\ApiDoc\Generators\OpenApiGenerator;

class ApiDocController
{
    public function index(OpenApiGenerator $generator): View
    {
        $spec = $generator->generate();

        return view('sculpt::docs', [
            'spec'  => json_encode($spec, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'title' => config('sculpt-apidoc.title', 'Sculpt API Documentation'),
        ]);
    }
}