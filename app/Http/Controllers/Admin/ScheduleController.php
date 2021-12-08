<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        return Schedule::all();
    }

    public function getAll(Request $request)
    {
        $schedules = Schedule::query();

        $per_page = $request->query('per_page') ? $request->query('per_page') : 10;
        $sortBy = $request->query('sortBy');
        $sortDesc = $request->query('sortDesc');

        if($sortBy){
            foreach($sortBy as $key => $sort){
                if($sortDesc[$key] == "true"){
                    $schedules->orderByDesc($sort);
                }
                else{
                    $schedules->orderBy($sort);
                }
            }
        }

        return $schedules->paginate($per_page);

    }

    public function create(Request $request)
    {
        $request->validate([
            "time_start" => "required",
            "time_end" => "required",
            "day" => "required",
        ]);

        $schedule = Schedule::create([
            "time_start" => $request->time_start.":00",
            "time_end" => $request->time_end.":00",
            "day" => $request->day,
        ]);

        return $schedule;
    }

    public function update(Schedule $schedule,Request $request)
    {
        $request->validate([
            "time_start" => "required",
            "time_end" => "required",
            "day" => "required",
        ]);

        $schedule->update([
            "time_start" => $request->time_start,
            "time_end" => $request->time_end,
            "day" => $request->day,
        ]);

        return $schedule;
    }

    public function delete(Schedule $schedule)
    {
        if($schedule->class_details()->count() > 0)
            return ["error" => "This schedule belongs to a class"];

        return $schedule->delete();
    }
}
