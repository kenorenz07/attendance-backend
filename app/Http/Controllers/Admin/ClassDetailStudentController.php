<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassDetailStudent;
use Illuminate\Http\Request;

class ClassDetailStudentController extends Controller
{
    public function chekRemark($remark,$name)
    {
        switch($remark){
            case Attendance::ABSENT :
                return $name ? "Absent" : "error";
                break;
            case Attendance::PRESENT : 
                return $name ? "Present" : "success";
                break;
            case Attendance::LATE : 
                return $name ? "Late" : "warning";
                break;
            case Attendance::EXCUSED : 
                return $name ? "Excused" : "info";
                break;
            default:
                return "Not defined";
        }
    }

    public function getAttendance(ClassDetailStudent $class_detail_student,Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        $attendances = $class_detail_student->attendances()->whereBetween('date_of_attendance',[$start_date,$end_date])->get();
        
        $attendances->map(function($attendance, $key) {
            return [
                "name" => $this->checkRemark($attendance->remarks,true),
                "start" => $attendance->date_of_attendance,
                "end" => $attendance->date_of_attendance,
                "color" => $this->checkRemark($attendance->remarks,false),
                "timed" => false,
            ];
        });

        return $attendances;

    }
}
