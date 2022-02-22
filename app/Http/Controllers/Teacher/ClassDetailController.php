<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassDetail;
use App\Models\ClassDetailStudent;
use App\Models\Subject;
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
        return $class_detail;
    }

    public function parseDay($day)
    {
        switch($day) {
            case 'MONDAY' :
                return 1;
                break;
            case 'TUESDAY' :
                return 2;
                break;
            case 'WEDNESDAY' :
                return 3;
                break;
            case 'THURSDAY' :
                return 4;
                break;
            case 'FRIDAY' :
                return 5;
                break;
            case 'SATURDAY' :
                return 6;
                break;
            case 'SUNDAY' :
                return 0;
                break;
            
        }
    }

    public function getDatesForFilter(ClassDetail $class_detail)
    {
        $days = [];
        $startDate = Carbon::parse($class_detail->start_date)->next($this->parseDay($class_detail->schedule->day)); // Get the first friday.
        $endDate = Carbon::parse($class_detail->end_date);
        
        for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
            $days[] = $date->format('m/d/Y');
        }

        return $days;
    }
    public function getAttendances(ClassDetail $class_detail,Request $request) 
    {
        $class_students = $class_detail->students()->with('student')->get();
        $class_attendance = [];

        foreach($class_students as $class_student){
            $attendance = $class_student->attendances()->whereDate('date_of_attendance',Carbon::createFromFormat('m/d/Y',$request->query('date_filter')))->first();

            if($attendance){
                $class_student = Arr::add($class_student,'attendance', $attendance);
                $class_attendance[] = $class_student;
            }
        }

        return $class_attendance;

    }

    public function addStudentToClass(ClassDetail $class_detail,Request $request)
    {
        return $class_detail->students()->create(['student_id' => $request->student_id]);
    }

    public function removeStudentFromClass(ClassDetailStudent $class_detail_student)
    {
        $class_detail_student->attendances()->delete();

        return $class_detail_student->delete();
    }

    public function updateStudentAttendance(Attendance $attendance,Request $request)
    {
        return $attendance->update([
            'remarks' => $request->remark
        ]);
    }
}
