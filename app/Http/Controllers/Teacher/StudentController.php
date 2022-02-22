<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function getAll()
    {
        return Student::all();
    }

    public function getAvailableStudents(Request $request)
    {
        $query = Student::query();

        if($request->query('class_id')){
            $query->whereDoesntHave('class_details', function ($query) use($request){
                $query->where('class_detail_id', $request->query('class_id'));
            });
        }
        return $query->get();
    }
}
