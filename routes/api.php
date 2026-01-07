<?php

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\BusinessOwnerMiddleware;
use Illuminate\Support\Facades\Route;

\Illuminate\Support\Facades\Broadcast::routes(['middleware' => [\App\Http\Middleware\CheckLogin::class]]);