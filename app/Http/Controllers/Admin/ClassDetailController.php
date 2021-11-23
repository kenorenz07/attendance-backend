<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassDetail;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
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
                ->WhereHas('room', function ($query) use($request){
                    return $query->where('name', 'LIKE', "%".$request->query('search_key')."%");
                })
                ->orWhereHas('subject', function ($query) use($request){
                    return $query->where('name', 'LIKE', "%".$request->query('search_key')."%");
                })
                ->orWhereHas('teacher', function ($query) use($request){
                    return $query->Where("last_name", 'LIKE', "%".$request->query('search_key')."%")
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

    public function getAvailable()
    {
        return ClassDetail::whereNull('teacher_id')->get();
    }

    public function create(Request $request)
    {
        $request->validate([
            "subject_id" => "required",
            "room_id" => "required",
            "schedule_id" => "required",
        ]);

        $check = ClassDetail::where([ 
            "subject_id" => $request->subject_id,
            "room_id" => $request->room_id,
            "schedule_id" => $request->schedule_id,
            "teacher_id" => $request->teacher_id,
        ])->exists();

        if($check) return ["error" => "Class is already created"];

        $class_detail = ClassDetail::create([
            "subject_id" => $request->subject_id,
            "room_id" => $request->room_id,
            "schedule_id" => $request->schedule_id,
        ]);

        return $class_detail;
    }

    public function update(ClassDetail $class_detail,Request $request)
    {
        $request->validate([
            "subject_id" => "required",
            "room_id" => "required",
            "schedule_id" => "required",
        ]);

        $check = ClassDetail::where([ 
            "subject_id" => $request->subject_id,
            "room_id" => $request->room_id,
            "schedule_id" => $request->schedule_id,
        ])->exists();

        if($check) return ["error" => "Class is already created"];

        $class_detail->update([
            "subject_id" => $request->subject_id,
            "room_id" => $request->room_id,
            "schedule_id" => $request->schedule_id,
            "teacher_id" => $request->teacher_id ? $request->teacher_id : null
        ]);

        return $class_detail;
    }

    public function delete(ClassDetail $class_detail)
    {
        if($class_detail->students()->exists()) return ["error" => "There are students that is in this class"];
        
        return $class_detail->delete();
    }

  
}
