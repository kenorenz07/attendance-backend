<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
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


        if(auth()->guard('teacher')->attempt(['username' => request('username'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'teacher']);
            
            $teacher = Teacher::select('teachers.*')->find(auth()->guard('teacher')->user()->id);
            $success =  $teacher;
            $success['token'] =  $teacher->createToken('MyApp',['teacher'])->accessToken; 

            return response()->json($success, 200);
        }else{ 
            return response()->json(['error' => ['Username and Password are Wrong.']], 200);
        }
    }

    public function update(Request $request)
    {
        $teacher = $request->user();

        $request->validate([
            "first_name" => "required",
            "middle_name" => "required",
            "last_name" => "required",
            "email" => "required|email",
            "username" => "required",
        ]);

        $teacher->update([
            "first_name" => $request->first_name,
            "middle_name" => $request->middle_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "username" => $request->username,
        ]);

        if(str_contains($request->image,'base64')){

            $fileName = uploadImage("teacher_",$request->image);
            
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

    public function resetPassword(Request $request)
    {
        $teacher = $request->user();

        $request->validate([
            "previous_password" => "required",
            "new_password" => "required",
            "confirm_password" => "required",
        ]);

        if(!Hash::check($teacher->password ,$request->previous_password))
            return ["error" => "Password incorrect"];
        else if($request->new_password == $request->confirm_password)
            return ["error" =>"New password does not match"];
        else {
            $teacher->update([
                "password" => bcrypt($request->new_password)
            ]);

            return "Reset password succesfull";
        }

    }

    public function logout(Request $request)
    {
        auth()->guard('teacher')->logout();

        $request->user()->token()->revoke();

        return "success";
    }


    public function details(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}
