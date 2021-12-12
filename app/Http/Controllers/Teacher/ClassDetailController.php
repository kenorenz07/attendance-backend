<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassDetail;
use App\Models\Subject;
use Illuminate\Http\Request;

class ClassDetailController extends Controller
{
    
    public function getAll(Request $request)
    {
        $teacher = $request->user();

        $class_details = $teacher->class_details();

        if($request->query('day')) {
            $class_details->whereHas('schedule', function ($query) use($request){
                return $query->where('day', $request->query('day'));
            });
        }

        if($request->query('subject')) {
            return $request->query('subject');
            $class_details->whereHas('subject', function ($query) use($request){
                return $query->where('name', $request->query('subject'));
            });
        }
       
        return $class_details->get();
    }

    public function show(Request $request,ClassDetail $class_detail)
    {
        return $class_detail;
    }
}
