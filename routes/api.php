<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Rutas API
 *
 * Este proyecto usa Inertia.js como arquitectura principal.
 * Si en el futuro necesitas endpoints API REST (para app móvil, integraciones, etc.),
 * puedes agregarlos aquí.
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request){
    return $request->user();
});