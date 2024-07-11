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
Route::get('/logout', [AuthController::class, 'logout'])->middleware("auth:api")->name('logout');

// Projects
Route::post('/projects', [\App\Http\Controllers\ProjectController::class, 'store'])->middleware("auth:api");
Route::get('/projects', [\App\Http\Controllers\ProjectController::class, 'index'])->middleware("auth:api");
Route::get('/projects/{projectId}', [\App\Http\Controllers\ProjectController::class, 'show'])->middleware("auth:api");
Route::put('/projects/{projectId}', [\App\Http\Controllers\ProjectController::class, 'update'])->middleware("auth:api");
Route::delete('/projects/{projectId}', [\App\Http\Controllers\ProjectController::class, 'destroy'])->middleware("auth:api");


// Tasks
Route::post('/projects/{projectId}/tasks', [\App\Http\Controllers\TaskController::class, 'store'])->middleware("auth:api");
Route::get('/projects/{projectId}/tasks', [\App\Http\Controllers\TaskController::class, 'index'])->middleware("auth:api");
Route::get('/projects/{projectId}/tasks/{taskId}', [\App\Http\Controllers\TaskController::class, 'show'])->middleware("auth:api");
Route::put('/projects/{projectId}/tasks/{taskId}', [\App\Http\Controllers\TaskController::class, 'update'])->middleware("auth:api");
Route::delete('/projects/{projectId}/tasks/{taskId}', [\App\Http\Controllers\TaskController::class, 'destroy'])->middleware("auth:api");