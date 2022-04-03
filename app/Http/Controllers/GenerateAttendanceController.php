<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class GenerateAttendanceController extends Controller
{
    public function generateAttendance(ClassDetail $classDetail)
    {
        $data = [
            "class_detail" => $classDetail,
            "days" => $this->getDatesForFilter($classDetail),
            "attendances" => $this->getClassAttendance($classDetail)
        ];
        
        // return $data;
        // return view('attendance_pdf',$data);
        $pdf = PDF::loadView('attendance_pdf',$data)->setPaper('letter', 'landscape');
        // return $pdf->download('ss.pdf');
        // return $pdf->download('itsolutionstuff.pdf');
        return $pdf->download('Class#'.$classDetail->id.'_AttendanceSummary'.'.pdf');

        
    }

    public function getClassAttendance($classDetail)
    {
        $attendances = [];

        foreach($classDetail->students as $student) {
            $student_attendances = [];

            foreach($this->getDatesForFilter($classDetail) as $day){
                $attendance = $student->attendances()->whereDate('date_of_attendance',$day)->first();

                $student_attendances[$day] = $this->getStatus($attendance);
            }

            $attendances[$student->student->full_name] = $student_attendances;
        }

        return $attendances;
    }

    public function getStatus($attendance)
    {
        if(!$attendance)
            return 'no-class';

        switch($attendance->remarks){
            case Attendance::ABSENT :
                return "absent";
                break;
            case Attendance::PRESENT : 
                return "present";
                break;
            case Attendance::LATE : 
                return "late";
                break;
            case Attendance::EXCUSED : 
                return "excused";
                break;
            default:
                return "Not defined";
        }
    }

    public function parseDay($day)
    {
        switch($day) {
            case 'MONDAY' :
                return "this monday";
                break;
            case 'TUESDAY' :
                return "this tuesday";
                break;
            case 'WEDNESDAY' :
                return "this wednesday";
                break;
            case 'THURSDAY' :
                return "this thursday";
                break;
            case 'FRIDAY' :
                return "this friday";
                break;
            case 'SATURDAY' :
                return "this saturday";
                break;
            case 'SUNDAY' :
                return "this sunday";
                break;
            
        }
    }

    public function getDatesForFilter($class_detail)
    {
        $days = [];
        $startDate = Carbon::parse($class_detail->start_date)->modify($this->parseDay($class_detail->schedule->day)); // Get the first friday.
        $endDate = Carbon::parse($class_detail->end_date);
        
        for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
            $days[] = $date->format('m/d/Y');
        }

        return $days;
    }
}
