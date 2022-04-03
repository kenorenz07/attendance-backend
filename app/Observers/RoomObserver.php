<?php

namespace App\Observers;

use App\Models\Room;

class RoomObserver
{
    /**
     * Handle the Room "created" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function created(Room $room)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin created room #".$room->id.' '.$room->name
            ]);
        }
    }

    /**
     * Handle the Room "updated" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function updated(Room $room)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin updated room #".$room->id.' '.$room->name
            ]);
        }
    }

    /**
     * Handle the Room "deleted" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function deleted(Room $room)
    {
        if(auth()->user()) {

            auth()->user()->logs()->create([
                "message" => "Admin deleted room #".$room->id.' '.$room->name
            ]);
        }
    }

    /**
     * Handle the Room "restored" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function restored(Room $room)
    {
        //
    }

    /**
     * Handle the Room "force deleted" event.
     *
     * @param  \App\Models\Room  $room
     * @return void
     */
    public function forceDeleted(Room $room)
    {
        //
    }
}
