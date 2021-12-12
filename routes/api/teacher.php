<?php

use App\Http\Controllers\Teacher\AuthenticationController;
use App\Http\Controllers\Teacher\ClassDetailController;
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

Route::group( ['prefix' => '/v1','middleware' => ['auth:teacher-api','scopes:teacher'] ],function(){
 
    //Authentication Controller
    Route::get('/details',[AuthenticationController::class, 'details']);

    Route::post('/logout',[AuthenticationController::class, 'logout']);

    // CLASS DETAILS 
    Route::get('class-details',[ClassDetailController::class,'getAll']);
    Route::get('class-detail/{class_detail}',[ClassDetailController::class,'show']);
});