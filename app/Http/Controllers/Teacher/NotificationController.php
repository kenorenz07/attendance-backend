<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getAll(Teacher $teacher)
    {
        return $teacher->notifications()->with('class_detail','notifiable')->orderBy('created_at','DESC')->get();
    }
}
