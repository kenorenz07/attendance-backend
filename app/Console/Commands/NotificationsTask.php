<?php

namespace App\Console\Commands;

use App\Models\ClassDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotificationsTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $weekMap = ['SUNDAY','MONDAY', 'TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY'];

        $classes = ClassDetail::whereHas('schedule', function ($query) use($weekMap){
            return $query->where('day',$weekMap[Carbon::now()->dayOfWeek]);
        })->get();

        foreach($classes as $class){
            $start_time = Carbon::parse($class->schedule->time_start);
            $end_time = Carbon::parse($class->schedule->time_end);

            $now = Carbon::now();

            foreach($class->students as $student) {
                $attendance_exists = $student->attendances->whereDate("date_of_attendance",Carbon::now())->exists();
                if(!$attendance_exists){

                    if($now->diffInMinutes($start_time) < 0) {
                        $class->notifications()->create([
                            "name" => "Starting in ".$now->diffInMinutes($start_time). " mins",
                            "student_id" => $student->student_id
                        ]);
                    }
                    else if($now->diffInMinutes($start_time) > 0) {
                        $class->notifications()->create([
                            "name" => $now->diffInMinutes($start_time)." mins late",
                            "student_id" => $student->student_id
                        ]);
                    }


                }
            }
        }
    }
}
