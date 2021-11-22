<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Attendance;
use App\Models\ClassDetail;
use App\Models\ClassDetailStudent;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Admin::create([
            'username' => 'admin_test',
            'name' => 'John Doe',
            'password' => bcrypt(123123),
            'is_super' => true
        ]);

        $room = Room::create([
            'name' => "Room1",'node_key' => "Room1", 'seats' => 12
        ]);

        $sched = Schedule::create([
            'time_start' => '08:22','time_end' => '20:00','day' => 'm'
        ]);

        $subject = Subject::create([
            'name'=> "Subject 1 ", 'description'=> "Subject 1 "
        ]);

        $teacher = Teacher::create([
            "first_name" => "teacher 1",
            "middle_name" => "teacher 1",
            "last_name" => "teacher 1",
            "rfid_number" => "teacher 1",
            "email" => "teacher@email.com",
            "username" => "teacher 1",
            "password" => "teacher 1",
        ]);

        $student = Student::create([
            "first_name" => "student 1",
            "middle_name" => "student 1",
            "last_name" => "student 1",
            "rfid_number" => "student 1",
            "email" => "student@email.com",
            "username" => "student 1",
            "password" => "student 1",
        ]);

        $class = ClassDetail::create([
            "room_id" => $room->id,
            "subject_id" => $subject->id,
            "schedule_id" => $sched->id,
            "teacher_id" => $teacher->id
        ]);

        $class_st = ClassDetailStudent::create([
            'class_detail_id'=>$class->id,'student_id'=>$student->id
        ]);

        $att = Attendance::create([
            'remarks' =>1,
            'time_in' =>'08:22',
            'time_out' =>'08:22',
            'date_of_attendance' =>Carbon::now(),
            'class_detail_students_id' =>$class_st->id
        ]);
        
        $this->call(AdminSeeder::class);
        Artisan::call('passport:install');
        Artisan::call('storage:link');
        Artisan::call('key:generate');
    }
}
