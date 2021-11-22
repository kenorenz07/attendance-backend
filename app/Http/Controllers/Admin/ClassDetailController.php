<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassDetail;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Http\Request;

class ClassDetailController extends Controller
{
    public function getAvailable()
    {
        return [
            "availabe_classes" => ClassDetail::whereNull('teacher_id')->get(),
            "subjects" => Subject::all(),
            "rooms" => Room::all(),
            "schedules" => Schedule::all(),
        ];
    }

    public function create(Request $request)
    {
        $request->validate([
            "subject_id" => "required",
            "room_id" => "required",
            "schedule_id" => "required",
            "teacher_id" => "required",
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
            "teacher_id" => $request->teacher_id
        ]);

        return $class_detail;
    }
}
