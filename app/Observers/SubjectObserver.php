<?php

namespace App\Observers;

use App\Models\Subject;

class SubjectObserver
{
    /**
     * Handle the Subject "created" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function created(Subject $subject)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin created subject #".$subject->id.' '.$subject->name
            ]);
        }
    }

    /**
     * Handle the Subject "updated" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function updated(Subject $subject)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin updated subject #".$subject->id.' '.$subject->name
            ]);
        }
    }

    /**
     * Handle the Subject "deleted" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function deleted(Subject $subject)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin deleted subject #".$subject->id.' '.$subject->name
            ]);
        }
    }

    /**
     * Handle the Subject "restored" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function restored(Subject $subject)
    {
        //
    }

    /**
     * Handle the Subject "force deleted" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function forceDeleted(Subject $subject)
    {
        //
    }
}
