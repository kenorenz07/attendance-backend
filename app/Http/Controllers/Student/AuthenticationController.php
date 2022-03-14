<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $validator = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);


        if(auth()->guard('student')->attempt(['username' => request('username'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'student']);
            
            $student = Student::select('students.*')->find(auth()->guard('student')->user()->id);
            $success =  $student;
            $success['token'] =  $student->createToken('MyApp',['student'])->accessToken; 

            return response()->json($success, 200);
        }else{ 
            return response()->json(['error' => ['Username and Password are Wrong.']], 200);
        }
    }

    public function logout(Request $request)
    {
        auth()->guard('student')->logout();

        $request->user()->token()->revoke();

        return "success";
    }

    public function update(Request $request)
    {

        $student = $request->user();

        $request->validate([
            "first_name" => "required",
            "middle_name" => "required",
            "last_name" => "required",
            "email" => "required|email",
            "username" => "required",
        ]);

        $student->update([
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "username" => $request->username,
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

    public function resetPassword(Request $request)
    {
        $student = $request->user();

        $request->validate([
            "previous_password" => "required",
            "new_password" => "required",
            "confirm_password" => "required",
        ]);

        if(!Hash::check($student->password ,$request->previous_password))
            return ["error" => "Password incorrect"];
        else if($request->new_password == $request->confirm_password)
            return ["error" =>"New password does not match"];
        else {
            $student->update([
                "password" => bcrypt($request->new_password)
            ]);

            return "Reset password succesfull";
        }

    }


    public function details(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}
