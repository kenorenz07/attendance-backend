<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ClassDetailController extends Controller
{
    public function getAll(Request $request)
    {
        $teacher = $request->user();

        $class_details = ClassDetail::query()->where('teacher_id', $teacher->id);

        if($request->query('day')) { 
            $class_details->whereHas('schedule', function ($query) use($request){
                return $query->where('day', $request->query('day'));
            });
        }

        if($request->query('subject_key')) {
            $class_details->whereHas('subject', function ($query) use($request){
                return $query->where('name', $request->query('subject_key'));
            });
        }
       
        return $class_details->get();
    }

    public function show(Request $request,ClassDetail $class_detail)
    {
        $class_student = $class_detail->students()->where('student_id',$request->user()->id)->first();

        $class_detail = Arr::add($class_detail,'statistics', $this->getStudentStatistics($class_student));

        return $class_detail;
    }

    public function getStudentStatistics($class_student)
    {
        return [
            "absent" => $class_student->attendances()->where('remarks',Attendance::ABSENT)->count(),
            "present" => $class_student->attendances()->where('remarks',Attendance::PRESENT)->count(),
            "late" => $class_student->attendances()->where('remarks',Attendance::LATE)->count(),
            "excused" => $class_student->attendances()->where('remarks',Attendance::EXCUSED)->count(),
        ];
    }

    public function getAttendances(ClassDetail $class_detail,Request $request) 
    {
        $class_student = $class_detail->students()->where('student_id',$request->user()->id)->get();

        $attendances = $class_student->attendances()->orderBy('date','DESC')->get();

        return $attendances;

    }
}
