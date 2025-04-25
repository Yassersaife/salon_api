<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SalonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

## ---------------------------------- AUTH MODULE 

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function(){

    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::put('/user', [AuthController::class, 'updateUser']);
    Route::delete('/user', [AuthController::class, 'deleteUser']);

});

## ---------------------------------- Salon MODULE 
Route::prefix('salon')->controller(SalonController::class)->group(function(){

 Route::get('/','index');
 Route::get('/latest','latest');
 Route::get('/{id}','show');
 Route::post('/','store');
 Route::put('/{id}','update');
 Route::delete('/{id}','delete');

});
