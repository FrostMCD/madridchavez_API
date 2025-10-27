<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZonaController;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/zonas',[ZonaController::class,'obtenerZonas']); //PLURAL
Route::get('/zona/{idzona}', [ZonaController::class,'obtenerZona']); //SINGULAR
Route::get('/zonaspais/{idpais}', [ZonaController::class,'obtenerZonaPais']); 
Route::post('/nuevazona', [ZonaController::class,'crearZona']); 

// Rutas de autenticación
Route::post('/nuevousuario', [AuthController::class, 'crearUsuario']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/usuario', [AuthController::class, 'obtenerUsuario']);
    Route::post('/logout', [AuthController::class, 'logout']);
});