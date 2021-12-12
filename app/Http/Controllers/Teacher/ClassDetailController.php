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

        $class_details = ClassDetail::query()->where('teacher_id', $teacher->id);

        if($request->query('day')) {
            $class_details->whereHas('schedule', function ($query) use($request){
                return $query->where('day', $request->query('day'));
            });
        }

        if($request->query('subject_key')) {
            $class_details->whereHas('subject', function ($query) use($request){
                return $query->where('name', $request->query('subject_key'));
            });
        }
       
        return $class_details->get();
    }

    public function show(Request $request,ClassDetail $class_detail)
    {
        return $class_detail;
    }
}
