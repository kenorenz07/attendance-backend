<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getAll(Request $request)
    {
        return $request->user()->notifications()->with('class_detail','notifiable')->orderBy('created_at','DESC')->get();
    }

    public function countToday(Request $request)
    {
        return $request->user()->notifications()->whereDate('created_at',Carbon::now())->count();
    }
}
