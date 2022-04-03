<?php

namespace App\Observers;

use App\Models\Attendance;

class AttendanceObserver
{

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

    /**
     * Handle the Attendance "created" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function created(Attendance $attendance)
    {
        $student = $attendance->class_detail_student;

        $student->student()->logs()->create([
            "message" => "Student is ".$this->getStatus($attendance).' at '.$attendance->time_in.' in class #'.$attendance->class_detail_student->class_detail->id.' subject '.$attendance->class_detail_student->class_detail->subject->name
        ]);
    }

    /**
     * Handle the Attendance "updated" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function updated(Attendance $attendance)
    {
        //
    }

    /**
     * Handle the Attendance "deleted" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function deleted(Attendance $attendance)
    {
        //
    }

    /**
     * Handle the Attendance "restored" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function restored(Attendance $attendance)
    {
        //
    }

    /**
     * Handle the Attendance "force deleted" event.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return void
     */
    public function forceDeleted(Attendance $attendance)
    {
        //
    }
}
