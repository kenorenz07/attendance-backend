<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassDetail;
use App\Models\ClassDetailStudent;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassDetailController extends Controller
{
    
    public function getAll(Request $request)
    {
        $class_details = ClassDetail::query();

        $per_page = $request->query('per_page') ? $request->query('per_page') : 10;
        $sortBy = $request->query('sortBy');
        $sortDesc = $request->query('sortDesc');

        if($request->query('search_key')){
            $class_details
                ->whereHas('room', function ($query) use($request){
                    return $query->where('name', 'LIKE', "%".$request->query('search_key')."%");
                })
                ->orWhereHas('subject', function ($query) use($request){
                    return $query->where('name', 'LIKE', "%".$request->query('search_key')."%");
                })
                ->orWhereHas('section', function ($query) use($request){
                    return $query->where('name', 'LIKE', "%".$request->query('search_key')."%");
                })
                ->orWhereHas('teacher', function ($query) use($request){
                    return $query->where("last_name", 'LIKE', "%".$request->query('search_key')."%")
                        ->orWhere("first_name", 'LIKE', "%".$request->query('search_key')."%")
                        ->orWhere("middle_name", 'LIKE', "%".$request->query('search_key')."%")
                        ->orWhere("email", 'LIKE', "%".$request->query('search_key')."%")
                        ;
                })
                ;
        }

        if($sortBy){
            foreach($sortBy as $key => $sort){
                if($sortDesc[$key] == "true"){
                    $class_details->orderByDesc($sort);
                }
                else{
                    $class_details->orderBy($sort);
                }
            }
        }

        return $class_details->paginate($per_page);
    }

    public function getAvailable(Request $request)
    {
        if($request->query('student_id')) {
            return ClassDetail::whereDoesntHave('students', function ($query) use($request){
                $query->where('student_id', $request->query('student_id'));
            })->get();
        } 
        else {
            return ClassDetail::whereNull('teacher_id')->get();
        }
    }

    public function getClassStudents(ClassDetail $class_detail,Request $request)
    {
        $per_page = $request->query('per_page') ? $request->query('per_page') : 5;

        // $students = $class_detail->students()->query();
        $students = ClassDetailStudent::query()->with('student');

        $students->where('class_detail_id',$class_detail->id);

        if($request->query("search_key")){
            $students->whereHas('student', function ($query) use($request){
                return $query->where("last_name", 'LIKE', "%".$request->query('search_key')."%")
                ->orWhere("first_name", 'LIKE', "%".$request->query('search_key')."%")
                ->orWhere("middle_name", 'LIKE', "%".$request->query('search_key')."%")
                ->orWhere("email", 'LIKE', "%".$request->query('search_key')."%");
            });
        }
        
        return $students->paginate($per_page);
    }

    public function addClassStudents(ClassDetail $class_detail,Request $request)
    {
        foreach($request->student_ids as $id ) {
            if(!$class_detail->students()->where('student_id',$id)->exists()){
                $class_detail->students()->create([
                    "student_id" => $id 
                ]);
            }
        }
        return 'Success';
    }
    public function show(ClassDetail $class_detail)
    {
        return $class_detail;
    }
    public function create(Request $request)
    {
        $request->validate([
            "subject_id" => "required",
            "room_id" => "required",
            "schedule_id" => "required",
            "section_id" => "required",
        ]);

        $check = ClassDetail::where([ 
            "subject_id" => $request->subject_id,
            "room_id" => $request->room_id,
            "schedule_id" => $request->schedule_id,
            "teacher_id" => $request->teacher_id,
            "section_id" => $request->section_id,
        ])->exists();

        if($check) return ["error" => "Class already"];

        $class_detail = ClassDetail::create([
            "subject_id" => $request->subject_id,
            "room_id" => $request->room_id,
            "section_id" => $request->section_id,
            "schedule_id" => $request->schedule_id,
            "teacher_id" => $request->teacher_id ? $request->teacher_id : null,
            "start_date" => Carbon::parse($request->start_date),
            "end_date" => Carbon::parse($request->end_date),
        ]);

        return $class_detail;
    }

    public function update(ClassDetail $class_detail,Request $request)
    {
        $request->validate([
            "subject_id" => "required",
            "room_id" => "required",
            "schedule_id" => "required",
            "section_id" => "required",
        ]);

        $check = ClassDetail::where([ 
            "subject_id" => $request->subject_id,
            "room_id" => $request->room_id,
            "schedule_id" => $request->schedule_id,
            "section_id" => $request->section_id,
        ])->exists();
        
        
        if($check &&
            $request->subject_id != $class_detail->subject_id && 
                $request->room_id != $class_detail->room_id && 
                    $request->schedule_id != $class_detail->schedule_id &&
                        $request->section_id != $class_detail->section_id) 
            return ["error" => "Class already exists"];

        $class_detail->update([
            "subject_id" => $request->subject_id,
            "room_id" => $request->room_id,
            "schedule_id" => $request->schedule_id,
            "section_id" => $request->section_id,
            "teacher_id" => $request->teacher_id ? $request->teacher_id : null,
            "start_date" => Carbon::parse($request->start_date),
            "end_date" => Carbon::parse($request->end_date),
        ]);

        return $class_detail;
    }

    public function addStudentToClass(ClassDetail $class_detail,Request $request)
    {
        return $class_detail->students()->create(['student_id' => $request->student_id]);
    }
    
    public function removeStudentFromClass(ClassDetail $class_detail,Request $request)
    {
        $student_class_detail = $class_detail->students()->where('student_id',$request->student_id)->first();
        
        $student_class_detail->attendances()->delete();

        return $student_class_detail->delete();
    }

    public function delete(ClassDetail $class_detail)
    {
        if($class_detail->students()->exists()) return ["error" => "There are students that is in this class"];
        
        return $class_detail->delete();
    }
    

  
}
