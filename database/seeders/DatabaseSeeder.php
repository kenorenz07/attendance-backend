<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Attendance;
use App\Models\ClassDetail;
use App\Models\ClassDetailStudent;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Section;
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
            'is_super' => 1,
            'password' => bcrypt(123123),
        ]);

        $this->call(AdminSeeder::class);

        Room::factory()->count(10)->create();

        Schedule::factory()->count(10)->create();

        Subject::factory()->count(10)->create();

        Section::factory()->count(10)->create();

        Teacher::factory()->count(10)->create();

        Student::factory()->count(10)->create();

        for($int = 1; $int < 12; $int++){

            $room_id = rand(1,10);
            $subject_id = rand(1,10);
            $schedule_id = rand(1,10);
            $section_id = rand(1,10);
            $teacher_id = rand(1,10);

            if(!ClassDetail::where([
                "room_id" => $room_id,
                "subject_id" => $subject_id,
                "schedule_id" => $schedule_id,
                "section_id" => $section_id,
            ])->exists()) {
                $class = ClassDetail::create([
                    "room_id" => $room_id,
                    "subject_id" => $subject_id,
                    "schedule_id" => $schedule_id,
                    "teacher_id" => $teacher_id,
                    "section_id" => $section_id,
                    "start_date" => Carbon::now()->startOfYear(),
                    "end_date" => Carbon::now()->endOfYear()
                ]);
            }
        }

        $class_details = ClassDetail::all();

        foreach($class_details as $class_detail) {
            
            for($i = 1; $i < 5; $i++){
                $class_detail->students()->create([
                    'student_id'=>rand(1,10)
                ]);
            }
        
        }
        
        // $this->call(AttendanceSeeder::class);fsfs

        Artisan::call('passport:install');
        Artisan::call('storage:link');
        Artisan::call('key:generate');
    }
}
