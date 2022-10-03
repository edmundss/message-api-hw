<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ClientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('client/{client}/notifications', [ClientController::class, 'getNotifications'])->middleware('auth:sanctum');
    Route::apiResource('client', ClientController::class)->except('index');
    Route::apiResource('client', ClientController::class)->only('index')->middleware('auth:sanctum');

    Route::post('notification/bulk', [NotificationController::class, 'bulkStore'])->middleware('auth:sanctum');
    Route::apiResource('notification', NotificationController::class)->middleware('auth:sanctum');
});
