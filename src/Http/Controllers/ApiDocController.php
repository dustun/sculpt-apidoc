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
            'spec'  => $spec,
            'title' => config('sculpt-apidoc.title', 'Sculpt API Documentation'),
        ]);
    }
}