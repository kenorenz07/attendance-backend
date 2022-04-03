<?php

namespace App\Observers;

use App\Models\Teacher;

class TeacherObserver
{
    /**
     * Handle the Teacher "created" event.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return void
     */
    public function created(Teacher $teacher)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin created teacher #".$teacher->id.' '.$teacher->full_name
            ]);
        }
    }

    /**
     * Handle the Teacher "updated" event.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return void
     */
    public function updated(Teacher $teacher)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin updated teacher #".$teacher->id.' '.$teacher->full_name
            ]);
        }
    }

    /**
     * Handle the Teacher "deleted" event.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return void
     */
    public function deleted(Teacher $teacher)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin deleted teacher #".$teacher->id.' '.$teacher->full_name
            ]);
        }
    }

    /**
     * Handle the Teacher "restored" event.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return void
     */
    public function restored(Teacher $teacher)
    {
        //
    }

    /**
     * Handle the Teacher "force deleted" event.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return void
     */
    public function forceDeleted(Teacher $teacher)
    {
        //
    }
}
