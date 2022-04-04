<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\ClassDetail;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Database\Seeders\AttendanceSeeder;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $validator = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);


        if(auth()->guard('admin')->attempt(['username' => request('username'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'admin']);
            
            $admin = Admin::select('admins.*')->find(auth()->guard('admin')->user()->id);
            $success =  $admin;
            $success['token'] =  $admin->createToken('MyApp',['admin'])->accessToken; 

            $admin->logs()->create([
                "message" => "Admin logged in the backoffice"
            ]);

            return response()->json($success, 200);
        }else{ 
            return response()->json(['error' => ['Username and Password are Wrong.']], 200);
        }
    }

    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();

        $request->user()->logs()->create([
            "message" => "Admin logged out the backoffice"
        ]);

        $request->user()->token()->revoke();

        return "success";
    }


    public function details(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function statistics($year)
    {
        if(!$year) $year = Carbon::now()->year;
        
        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];

        $attendances = [];

        for($status = 0; $status <= 3; $status++){
        
            $month_attendance = [];
            
            foreach($months as $month){
               $month_attendance[] = Attendance::whereYear('created_at',$year)->whereMonth('created_at',$month)->where('remarks',$status)->count();
            }
    
            $attendances[] = $month_attendance;
    
          }

        return [
            'teachers' => Teacher::count(),
            'students' => Student::count(),
            'class_details' => ClassDetail::count(),
            'attendances_total' => Attendance::count(),
            'attendances' => $attendances
        ];
    }
}
