<?php

namespace App\Observers;

use App\Models\Schedule;

class ScheduleObserver
{
    /**
     * Handle the Schedule "created" event.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return void
     */
    public function created(Schedule $schedule)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin added schedule #".$schedule->id.' Day : '.$schedule->day.', Time start :'.$schedule->time_start. ', Time end : '.$schedule->time_end
            ]);
        }
    }

    /**
     * Handle the Schedule "updated" event.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return void
     */
    public function updated(Schedule $schedule)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin updated schedule #".$schedule->id.' Day : '.$schedule->day.', Time start :'.$schedule->time_start. ', Time end : '.$schedule->time_end
            ]);
        }
    }

    /**
     * Handle the Schedule "deleted" event.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return void
     */
    public function deleted(Schedule $schedule)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin deleted schedule #".$schedule->id.' Day : '.$schedule->day.', Time start :'.$schedule->time_start. ', Time end : '.$schedule->time_end
            ]);
        }
    }

    /**
     * Handle the Schedule "restored" event.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return void
     */
    public function restored(Schedule $schedule)
    {
        //
    }

    /**
     * Handle the Schedule "force deleted" event.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return void
     */
    public function forceDeleted(Schedule $schedule)
    {
        //
    }
}
