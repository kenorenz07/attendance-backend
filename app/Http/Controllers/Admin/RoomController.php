<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return Room::all();
    }

    public function getAll(Request $request)
    {
        $rooms = Room::query();

        $per_page = $request->query('per_page') ? $request->query('per_page') : 10;
        $sortBy = $request->query('sortBy');
        $sortDesc = $request->query('sortDesc');

        if($request->query('search_key')){
            $rooms
                ->where("name",'LIKE', "%".$request->query('search_key')."%")
                ->orWhere("node_key",'LIKE', "%".$request->query('search_key')."%");
        }

        if($sortBy){
            foreach($sortBy as $key => $sort){
                if($sortDesc[$key] == "true"){
                    $rooms->orderByDesc($sort);
                }
                else{
                    $rooms->orderBy($sort);
                }
            }
        }

        return $rooms->paginate($per_page);
    }

    public function create(Request $request)
    {
        $request->validate([
            "name" => "required",
            "node_key" => "required",
            "seats" => "required",
        ]);

        $room = Room::create([
            "name" => $request->name,
            "node_key" => $request->node_key,
            "seats" => $request->seats,
        ]);

        return $room;
    }

    public function update(Room $room,Request $request)
    {
        $request->validate([
            "name" => "required",
            "node_key" => "required",
            "seats" => "required",
        ]);

        $room->update([
            "name" => $request->name,
            "node_key" => $request->node_key,
            "seats" => $request->seats,
        ]);

        return $room;
    }

    public function delete(Room $room)
    {
        if($room->class_details()->count() > 0)
            return ["error" => "This room belongs to a class"];

        return $room->delete();
    }
}
