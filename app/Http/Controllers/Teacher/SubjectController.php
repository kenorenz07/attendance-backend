<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function getAll(Request $request)
    {
        return Subject::whereHas('class_details',function($query) use($request) {
            return $query->where('teacher_id',$request->user()->id);
        })->get();
    }
}
