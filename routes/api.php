<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');

Route::group([
	'middleware' => 'auth:sanctum'
], function () {
	Route::apiResource('/tasks', App\Http\Controllers\TaskController::class);
	Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
});
