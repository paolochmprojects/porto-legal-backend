<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::post('/register', [RegisterController::class, 'store']);

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/me', [AuthController::class, 'me'])->middleware("auth:api")->name('me');
Route::get('/refresh', [AuthController::class, 'refresh'])->middleware("auth:api")->name('refresh');

// Projects
Route::post('/projects', [\App\Http\Controllers\ProjectController::class, 'store'])->middleware("auth:api");
Route::delete('/projects/{id}', [\App\Http\Controllers\ProjectController::class, 'destroy'])->middleware("auth:api");
