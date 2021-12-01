<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function getAll(Request $request)
    {
        $students = Student::query();

        $per_page = $request->query('per_page') ? $request->query('per_page') : 10;
        $sortBy = $request->query('sortBy');
        $sortDesc = $request->query('sortDesc');

        if($request->query('search_key')){
            $students
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
                        if($sort == "display_name") $students->orderByDesc("first_name");
                        else $students->orderByDesc($sort);
                    }
                    else{
                        if($sort == "display_name") $students->orderBy("first_name");
                        else $students->orderBy($sort);
                    }
                }
            }
       }

        return $students->paginate($per_page);
    }

    public function show(Student $student)
    {
        return $student->load('class_details');
    }

    public function index(Request $request)
    {
        $query = Student::query();

        if($request->query('class_id')){
            $query->whereDoesntHave('class_details', function ($query) use($request){
                $query->where('class_detail_id', $request->query('class_id'));
            });
        }
        return $query->get();
    }

    public function classess(Student $student)
    {
        return $student->class_details()->paginate(6);
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

        $student = Student::create([
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "rfid_number" => $request->rfid_number,
            "email" => $request->email,
            "username" => $request->username,
            "password" => bcrypt($request->password),
        ]);

        if(str_contains($request->image,'base64')){
            $fileName = uploadImage("student_",$request->image);
            
            $student->image()->create([
                "name" => $fileName
            ]);
        }

        return $student;
    }

    public function update(Student $student, Request $request)
    {
        $request->validate([
            "first_name" => "required",
            "middle_name" => "required",
            "last_name" => "required",
            "rfid_number" => "required",
            "email" => "required|email",
            "username" => "required",
        ]);

        $student->update([
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "rfid_number" => $request->rfid_number,
            "email" => $request->email,
            "username" => $request->username,
            "password" => $request->password ? bcrypt($request->password) : $student->password ,
        ]);

        if(str_contains($request->image,'base64')){

            $fileName = uploadImage("student_",$request->image);
            
            if($student->image){
                Storage::delete('/public/'.$student->image->name);
                $student->image()->delete();
            }
            
            $student->image()->create([
                "name" => $fileName
            ]);

        }

        return $student;
    }

    public function delete(Student $student)
    {
        if($student->class_details()->exists()) 
            return ["error" => "This student belongs to a class"];

        if($student->image){
            Storage::delete('/public/'.$student->image->name);
            $student->image()->delete();
        }

        return $student->delete();
    }


}
