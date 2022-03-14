<?php

use App\Http\Controllers\Student\AuthenticationController;
use App\Http\Controllers\Student\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login',[AuthenticationController::class, 'login']);

Route::group( ['prefix' => '/v1','middleware' => ['auth:student-api','scopes:student'] ],function(){
 
    //Authentication Controller
    Route::get('/details',[AuthenticationController::class, 'details']);

    Route::post('/logout',[AuthenticationController::class, 'logout']);

    Route::get('notifications', [NotificationController::class,'getAll']);

});