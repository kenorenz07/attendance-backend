<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthenticationController;
use App\Http\Controllers\Admin\ClassDetailController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
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

Route::group( ['prefix' => '/v1','middleware' => ['auth:admin-api','scopes:admin'] ],function(){
 
    //Authentication Controller
    Route::get('/details',[AuthenticationController::class, 'details']);
    Route::post('/logout',[AuthenticationController::class, 'logout']);

    // ADMIN
    Route::get('admin/all',[AdminController::class, 'getAll']);
    Route::post('admin/create',[AdminController::class,'create']);
    Route::put('admin/update/{admin}',[AdminController::class,'update']);
    Route::delete('admin/delete/{admin}/',[AdminController::class,'delete']);

    // TEACHER
    Route::get('teacher/all',[TeacherController::class,'getAll']);
    Route::get('teacher/index',[TeacherController::class,'index']);
    Route::get('teacher/classes/{teacher}',[TeacherController::class,'classess']);
    Route::get('teacher/{teacher}',[TeacherController::class,'show']);
    Route::post('teacher/create',[TeacherController::class,'create']);
    Route::put('teacher/update/{teacher}',[TeacherController::class,'update']);
    Route::delete('teacher/delete/{teacher}',[TeacherController::class,'delete']);

    // SUBJECTS
    Route::get('subject/index',[SubjectController::class,'index']);

    // ROOMS
    Route::get('room/index',[RoomController::class,'index']);

    // SCHEDULES
    Route::get('schedule/index',[ScheduleController::class,'index']);

    //CLASS DETAILS
    Route::get('class/available',[ClassDetailController::class,'getAvailable']);
    Route::get('class/all',[ClassDetailController::class,'getAll']);
    Route::post('class/create',[ClassDetailController::class,'create']);
    Route::put('class/update/{class_detail}',[ClassDetailController::class,'update']);
    Route::delete('class/delete/{class_detail}',[ClassDetailController::class,'delete']);
    

});