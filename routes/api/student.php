<?php

use App\Http\Controllers\Student\AuthenticationController;
use App\Http\Controllers\Student\ClassDetailController;
use App\Http\Controllers\Student\NotificationController;
use App\Http\Controllers\Student\SubjectController;
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
    Route::post('/update',[AuthenticationController::class, 'update']);
    Route::post('/reset-password',[AuthenticationController::class, 'resetPassword']);

    Route::get('class-details',[ClassDetailController::class,'getAll']);
    Route::get('class-detail/{class_detail}',[ClassDetailController::class,'show']);
    Route::get('class-detail/{class_detail}/get-attendances',[ClassDetailController::class,'getAttendances']);
    Route::get('class-detail/{class_detail}/get-days-filter',[ClassDetailController::class,'getDatesForFilter']);
    Route::delete('class-detail/student/{class_detail_student}/remove-student',[ClassDetailController::class,'removeStudentFromClass']);

    Route::get('subjects',[SubjectController::class,'getAll']);

    Route::get('notifications', [NotificationController::class,'getAll']);
    Route::get('notifications-today', [NotificationController::class,'countToday']);
    

});