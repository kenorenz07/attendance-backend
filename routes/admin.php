<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthenticationController;
use App\Http\Controllers\Admin\ClassDetailController;
use App\Http\Controllers\Admin\ClassDetailStudentController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\GenerateAttendanceController;
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
    Route::get('/statistics/{year}',[AuthenticationController::class, 'statistics']);

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

    // STUDENTS
    Route::get('student/all',[StudentController::class,'getAll']);
    Route::get('student/index',[StudentController::class,'index']);
    Route::get('student/classes/{student}',[StudentController::class,'classess']);
    Route::post('student/{student}/add-class',[StudentController::class,'assignToClass']);
    Route::get('student/{student}',[StudentController::class,'show']);
    Route::post('student/create',[StudentController::class,'create']);
    Route::put('student/update/{student}',[StudentController::class,'update']);
    Route::post('student/remove-class/{student}',[StudentController::class,'removeClassDetail']);
    Route::delete('student/delete/{student}',[StudentController::class,'delete']);    

    // SUBJECTS
    Route::get('subject/index',[SubjectController::class,'index']);
    Route::get('subject/all',[SubjectController::class,'getAll']);
    Route::get('subject/show/{subject}',[SubjectController::class,'show']);
    Route::get('subject/classes/{subject}',[SubjectController::class,'classess']);
    Route::post('subject/create',[SubjectController::class,'create']);
    Route::put('subject/update/{subject}',[SubjectController::class,'update']);
    Route::delete('subject/delete/{subject}/',[SubjectController::class,'delete']);

    // ROOMS
    Route::get('room/index',[RoomController::class,'index']);
    Route::get('room/all',[RoomController::class,'getAll']);
    Route::get('room/show/{room}',[RoomController::class,'show']);
    Route::get('room/classes/{room}',[RoomController::class,'classess']);
    Route::post('room/create',[RoomController::class,'create']);
    Route::put('room/update/{room}',[RoomController::class,'update']);
    Route::delete('room/delete/{room}/',[RoomController::class,'delete']);

    // SECTIONS
    Route::get('section/index',[SectionController::class,'index']);
    Route::get('section/all',[SectionController::class,'getAll']);
    Route::get('section/show/{section}',[SectionController::class,'show']);
    Route::get('section/classes/{section}',[SectionController::class,'classess']);
    Route::post('section/create',[SectionController::class,'create']);
    Route::put('section/update/{section}',[SectionController::class,'update']);
    Route::delete('section/delete/{section}/',[SectionController::class,'delete']);

    // SCHEDULES
    Route::get('schedule/index',[ScheduleController::class,'index']);
    Route::get('schedule/all',[ScheduleController::class,'getAll']);
    Route::get('schedule/show/{schedule}',[ScheduleController::class,'show']);
    Route::get('schedule/classes/{schedule}',[ScheduleController::class,'classess']);
    Route::post('schedule/create',[ScheduleController::class,'create']);
    Route::put('schedule/update/{schedule}',[ScheduleController::class,'update']);
    Route::delete('schedule/delete/{schedule}/',[ScheduleController::class,'delete']);

    //CLASS DETAILS
    Route::get('class/available',[ClassDetailController::class,'getAvailable']);
    Route::get('class/all',[ClassDetailController::class,'getAll']);
    Route::get('class/{class_detail}/students',[ClassDetailController::class,'getClassStudents']);
    Route::post('class/{class_detail}/add-students',[ClassDetailController::class,'addClassStudents']);
    Route::get('class/{class_detail}',[ClassDetailController::class,'show']);
    Route::post('class/create',[ClassDetailController::class,'create']);
    Route::put('class/update/{class_detail}',[ClassDetailController::class,'update']);
    Route::post('class/add-student/{class_detail}',[ClassDetailController::class,'addStudentToClass']);
    Route::post('class/remove-student/{class_detail}',[ClassDetailController::class,'removeStudentFromClass']);
    Route::delete('class/delete/{class_detail}',[ClassDetailController::class,'delete']);
    
    //CLASS DETAIL STUDENT
    Route::get('/class-detail-student/{class_detail_student}/attendances',[ClassDetailStudentController::class,"getAttendance"]);

    Route::get('generate-attendance-attendance/{classDetail}',[GenerateAttendanceController::class, 'generateAttendance']);

});