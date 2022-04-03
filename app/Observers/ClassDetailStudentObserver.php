<?php

namespace App\Observers;

use App\Models\ClassDetailStudent;

class ClassDetailStudentObserver
{
    /**
     * Handle the ClassDetailStudent "created" event.
     *
     * @param  \App\Models\ClassDetailStudent  $classDetailStudent
     * @return void
     */
    public function created(ClassDetailStudent $classDetailStudent)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin added student ".$classDetailStudent->student->full_name." to class #".$classDetailStudent->class_detail->id.' subject '.$classDetailStudent->class_detail->subject->name
            ]);
        }
    }

    /**
     * Handle the ClassDetailStudent "updated" event.
     *
     * @param  \App\Models\ClassDetailStudent  $classDetailStudent
     * @return void
     */
    public function updated(ClassDetailStudent $classDetailStudent)
    {
        //
    }

    /**
     * Handle the ClassDetailStudent "deleted" event.
     *
     * @param  \App\Models\ClassDetailStudent  $classDetailStudent
     * @return void
     */
    public function deleted(ClassDetailStudent $classDetailStudent)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin removed student ".$classDetailStudent->student->full_name." from class #".$classDetailStudent->class_detail->id.' subject '.$classDetailStudent->class_detail->subject->name
            ]);
        }
    }

    /**
     * Handle the ClassDetailStudent "restored" event.
     *
     * @param  \App\Models\ClassDetailStudent  $classDetailStudent
     * @return void
     */
    public function restored(ClassDetailStudent $classDetailStudent)
    {
        //
    }

    /**
     * Handle the ClassDetailStudent "force deleted" event.
     *
     * @param  \App\Models\ClassDetailStudent  $classDetailStudent
     * @return void
     */
    public function forceDeleted(ClassDetailStudent $classDetailStudent)
    {
        //
    }
}
