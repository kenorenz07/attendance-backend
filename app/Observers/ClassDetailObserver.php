<?php

namespace App\Observers;

use App\Models\ClassDetail;
use Illuminate\Support\Facades\Auth;

class ClassDetailObserver
{
    /**
     * Handle the ClassDetail "created" event.
     *
     * @param  \App\Models\ClassDetail  $classDetail
     * @return void
     */
    public function created(ClassDetail $classDetail)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin created class #".$classDetail->id.' with subject '.$classDetail->subject->name.', room'.$classDetail->room->name.', section'. $classDetail->section->name
            ]);
        }
    }

    /**
     * Handle the ClassDetail "updated" event.
     *
     * @param  \App\Models\ClassDetail  $classDetail
     * @return void
     */
    public function updated(ClassDetail $classDetail)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin updated class #".$classDetail->id.' to subject '.$classDetail->subject->name.', room'.$classDetail->room->name.', section'. $classDetail->section->name
            ]);
        }
    }

    /**
     * Handle the ClassDetail "deleted" event.
     *
     * @param  \App\Models\ClassDetail  $classDetail
     * @return void
     */
    public function deleted(ClassDetail $classDetail)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin deleted class #".$classDetail->id.' with subject '.$classDetail->subject->name.', room'.$classDetail->room->name.', section'. $classDetail->section->name
            ]);
        }
    }

    /**
     * Handle the ClassDetail "restored" event.
     *
     * @param  \App\Models\ClassDetail  $classDetail
     * @return void
     */
    public function restored(ClassDetail $classDetail)
    {
        //
    }

    /**
     * Handle the ClassDetail "force deleted" event.
     *
     * @param  \App\Models\ClassDetail  $classDetail
     * @return void
     */
    public function forceDeleted(ClassDetail $classDetail)
    {
        //
    }
}
