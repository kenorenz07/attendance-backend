<?php

namespace App\Console\Commands;

use App\Models\ClassDetail;
use App\Models\Notification;
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

            $teacher_notifications = $class->teacher->notifications()->where('class_detail_id',$class->id)->whereDate('created_at',Carbon::now())->count();

            if($now->diffInMinutes($start_time, false) > 0 && $now->diffInMinutes($start_time, false) < 20 && $teacher_notifications < 3){
                $class->teacher->notifications()->create([
                    "class_detail_id" => $class->id,
                    "name" => "Starting in ".$now->diffInMinutes($start_time). " mins",
                ]);
            }

            foreach($class->students as $student) {
                $attendance_exists = $student->attendances()->whereDate("date_of_attendance",Carbon::now())->exists();
                if(!$attendance_exists && $now->diffInMinutes($end_time,false) > 0){

                    if($now->diffInMinutes($start_time, false) < 20 && $now->diffInMinutes($start_time, false) > 0) {
                        $student->student->notifications()->create([
                            "class_detail_id" => $class->id,
                            "name" => "Starting in ".$now->diffInMinutes($start_time). " mins",
                        ]);
                    }
                    else if($now->diffInMinutes($start_time, false) < 0) {
                        $student->student->notifications()->create([
                            "class_detail_id" => $class->id,
                            "name" => $now->diffInMinutes($start_time)." mins late",
                        ]);
                    }
                }

            }
        }
    }
}
