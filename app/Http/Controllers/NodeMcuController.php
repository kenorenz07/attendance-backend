<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassDetail;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NodeMcuController extends Controller
{
    //

    public function authenticate($node_key,Request $request)
    {
        if($request->teacher_rfid) {
            $class_detail = ClassDetail::find($request->class_detail_id);
            
            $class_detail_student = $class_detail->students;

            foreach($class_detail_student as $student) {
                if(!$student->attendances()->whereDate('created_at',Carbon::now())->exists()){
                    $student->attendances()->create([
                        "time_in" => date('H:i:s'),
                        "time_out" => date('H:i:s'),
                        "remarks" => Attendance::ABSENT,
                        "date_of_attendance" => Carbon::now()
                    ]);
                }
                else {
                    $student->attendances()->whereDate('created_at',Carbon::now())->update([
                        "time_out" => date('H:i:s'),
                    ]);
                }

            }
            return [
                "authorized" => true,
            ];

        }
        else if($request->class_detail_id) {
            $class_detail = ClassDetail::find($request->class_detail_id);
            $student = Student::where('rfid_number',$request->rfid_number)->first();

            if(empty($student)) {
                return [
                    "alaws" => "alaws badi",
                ];
            }
            $student_class_detail = $class_detail->students()->where('student_id',$student->id)->first();

            if($student_class_detail) {
                $is_logged = $student_class_detail->attendances()->whereDate('created_at',Carbon::now())->exists();
                
                $now = Carbon::now();
                $schedule = Carbon::parse($class_detail->schedule->time_start);

                $remark = $now->diffInMinutes($schedule) > 15 ? Attendance::LATE : Attendance::PRESENT;

                if(!$is_logged){
                    $student_class_detail->attendances()->create([
                        "time_in" => date('H:i:s'),
                        "time_out" => date('H:i:s'),
                        "remarks" => $remark,
                        "date_of_attendance" => Carbon::now()
                    ]);
                }

                return [
                    "authorized" => true,
                    "class_detail_id" => $class_detail->id,
                    "teacher_rfid" => $class_detail->teacher->rfid_number,
                ];
            }
            else {
                return [    
                    "error" => "not found"
                ];
            }
            
        }
        else {
            $weekMap = ['SUNDAY','MONDAY', 'TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY'];

            $teacher = Teacher::where('rfid_number',$request->rfid_number)->first();
            $room = Room::where("node_key",$node_key)->first();
            $schedule = Schedule::whereTime('time_start', '<=', date('H:i:s'))->whereTime('time_end', '>=', date('H:i:s'))->where('day',$weekMap[Carbon::now()->dayOfWeek])->first();


            if(empty($teacher) || empty($room) || empty($schedule)){
                return [    
                    "error" => "not found"
                ];
            }

            // return [
            //     "room_id" => $room->id,
            //     "schedule_id" => $schedule->id,
            //     "teacher_id" => $teacher->id
            // ];

            $class_detail = ClassDetail::whereHas("room",function ($query) use($node_key){
                return $query->where("node_key",$node_key);
            })->whereHas('schedule', function ($query) use($weekMap){
                return $query->whereTime('time_start', '<=', date('H:i:s'))->whereTime('time_end', '>=', date('H:i:s'))->where('day',$weekMap[Carbon::now()->dayOfWeek]);
            })->whereHas('teacher', function ($query) use($request){
                return $query->where('rfid_number',$request->rfid_number);
            })->first();
            
            return [
                "authorized" => true,
                "class_detail_id" => $class_detail->id,
                "teacher_rfid" => $class_detail->teacher->rfid_number,
            ];

        }

        
    }
}
