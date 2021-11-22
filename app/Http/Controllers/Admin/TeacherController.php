<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function getAll(Request $request)
    {
        $teachers = Teacher::query();

        $per_page = $request->query('per_page') ? $request->query('per_page') : 10;
        $sortBy = $request->query('sortBy');
        $sortDesc = $request->query('sortDesc');

        if($request->query('search_key')){
            $teachers
                ->orWhere("username", 'LIKE', "%".$request->query('search_key')."%")
                ->orWhere("first_name", 'LIKE', "%".$request->query('search_key')."%")
                ->orWhere("middle_name", 'LIKE', "%".$request->query('search_key')."%")
                ->orWhere("last_name", 'LIKE', "%".$request->query('search_key')."%")
                ->orWhere("rfid_number", 'LIKE', "%".$request->query('search_key')."%")
                ->orWhere("email", 'LIKE', "%".$request->query('search_key')."%");
        }
        if($sortBy){
            foreach($sortBy as $key => $sort){
                if($sort != "image"){
                    if($sortDesc[$key] == "true"){
                        $teachers->orderByDesc($sort);
                    }
                    else{
                        $teachers->orderBy($sort);
                    }
                }
            }
       }

        return $teachers->paginate($per_page);
    }

    public function show(Teacher $teacher)
    {
        return $teacher->load('class_details');
    }

    public function create(Request $request)
    {
        $request->validate([
            "first_name" => "required",
            "middle_name" => "required",
            "last_name" => "required",
            "rfid_number" => "required",
            "email" => "required|email",
            "username" => "required",
            "password" => "required",
        ]);

        $teacher = Teacher::create([
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "rfid_number" => $request->rfid_number,
            "email" => $request->email,
            "username" => $request->username,
            "password" => bcrypt($request->password),
        ]);

        if(str_contains($request->image,'base64')){
            $fileName = uploadImage("teacher_",$request->image);
            
            $teacher->image()->create([
                "name" => $fileName
            ]);
        }

        return $teacher;
    }

    public function update(Teacher $teacher, Request $request)
    {
        $request->validate([
            "first_name" => "required",
            "middle_name" => "required",
            "last_name" => "required",
            "rfid_number" => "required",
            "email" => "required|email",
            "username" => "required",
        ]);

        $teacher->update([
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "rfid_number" => $request->rfid_number,
            "email" => $request->email,
            "username" => $request->username,
            "password" => $request->password ? bcrypt($request->password) : $teacher->password ,
        ]);

        if(str_contains($request->image,'base64')){

            $fileName = uploadImage("admin_",$request->image);
            
            if($teacher->image){
                Storage::delete('/public/'.$teacher->image->name);
                $teacher->image()->delete();
            }
            
            $teacher->image()->create([
                "name" => $fileName
            ]);

        }

        return $teacher;
    }

    public function delete(Teacher $teacher)
    {
        if($teacher->image){
            Storage::delete('/public/'.$teacher->image->name);
            $teacher->image()->delete();
        }

        return $teacher->delete();
    }
}
