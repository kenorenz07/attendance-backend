<?php

use App\Http\Controllers\Teacher\AuthenticationController;
use App\Http\Controllers\Teacher\ClassDetailController;
use App\Http\Controllers\Teacher\NotificationController;
use App\Http\Controllers\Teacher\StudentController;
use App\Http\Controllers\Teacher\SubjectController;
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
    Route::get('class-detail/{class_detail}/get-attendances',[ClassDetailController::class,'getAttendances']);
    Route::get('class-detail/{class_detail}/get-days-filter',[ClassDetailController::class,'getDatesForFilter']);
    Route::post('class-detail/{class_detail}/add-student',[ClassDetailController::class,'addStudentToClass']);
    Route::put('class-detial/update-attendance/{attendance}',[ClassDetailController::class,'updateStudentAttendance']);
    Route::delete('class-detail/student/{class_detail_student}/remove-student',[ClassDetailController::class,'removeStudentFromClass']);
    // SUBJECT 
    Route::get('subjects',[SubjectController::class,'getAll']);
    // STUDENT
    Route::get('students',[StudentController::class,'getAll']);
    Route::get('students/get-available',[StudentController::class,'getAvailableStudents']);

    Route::get('notifications', [NotificationController::class,'getAll']);
});