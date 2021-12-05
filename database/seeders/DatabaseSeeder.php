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
        ]);

        $this->call(AdminSeeder::class);

        Room::factory()->count(20)->create();

        Schedule::factory()->count(20)->create();

        Subject::factory()->count(20)->create();

        Teacher::factory()->count(20)->create();

        Student::factory()->count(100)->create();

        for($int = 1; $int < 100; $int++){

            $room_id = rand(1,20);
            $subject_id = rand(1,20);
            $schedule_id = rand(1,20);
            $teacher_id = rand(1,20);

            if(!ClassDetail::where([
                "room_id" => $room_id,
                "subject_id" => $subject_id,
                "schedule_id" => $schedule_id,
            ])->exists()) {
                $class = ClassDetail::create([
                    "room_id" => $room_id,
                    "subject_id" => $subject_id,
                    "schedule_id" => $schedule_id,
                    "teacher_id" => $teacher_id
                ]);
            }
        }

        $class_details = ClassDetail::all();

        foreach($class_details as $class_detail) {
            
            for($i = 1; $i < 5; $i++){
                $class_detail->students()->create([
                    'student_id'=>rand(1,20)
                ]);
            }
        
        }
        
        $this->call(AttendanceSeeder::class);

        Artisan::call('passport:install');
        Artisan::call('storage:link');
        Artisan::call('key:generate');
    }
}
