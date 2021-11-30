<?php

namespace Database\Seeders;

use App\Models\ClassDetailStudent;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes_details = ClassDetailStudent::all();

        foreach($classes_details as $class_detail_student){
            for($i = 0;$i < 5;$i++){
                $class_detail_student->attendances()->create([
                    "remarks" => rand(0,3),
                    "time_in" => '08:00',
                    "time_out" => '12:00',
                    "date_of_attendance" => Carbon::now()->addDays($i),
                ]);
            }

        }
        
    }
}
