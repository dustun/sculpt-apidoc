<?php

use Illuminate\Support\Facades\Route;
use Sculpt\ApiDoc\Http\Controllers\ApiDocController;

Route::get(config('sculpt-apidoc.route_prefix', 'docs'), [ApiDocController::class, 'index'])
    ->name('sculpt.docs.index');