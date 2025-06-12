<?php
declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeviceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::group(['prefix' => 'device', 'controller' => DeviceController::class, 'middleware' => ['auth:sanctum']], function () {
    Route::post('/register', 'register');
});
