<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return Subject::all();
    }

    public function getAll(Request $request)
    {
        $subjects = Subject::query();

        $per_page = $request->query('per_page') ? $request->query('per_page') : 10;
        $sortBy = $request->query('sortBy');
        $sortDesc = $request->query('sortDesc');

        if($request->query('search_key')){
            $subjects
                ->where("name",'LIKE', "%".$request->query('search_key')."%")
                ->orWhere("description",'LIKE', "%".$request->query('search_key')."%");
        }

        if($sortBy){
            foreach($sortBy as $key => $sort){
                if($sortDesc[$key] == "true"){
                    $subjects->orderByDesc($sort);
                }
                else{
                    $subjects->orderBy($sort);
                }
            }
        }

        return $subjects->paginate($per_page);

    }

    public function show(Subject $subject) 
    {
        return $subject->load('class_details');
    }

    public function classess(Subject $subject)
    {
        return $subject->class_details()->paginate(6);
    }

    public function create(Request $request)
    {
        $request->validate([
            "name" => "required",
            "description" => "required",
        ]);

        $subject = Subject::create([
            "name" => $request->name,
            "description" => $request->description,
        ]);

        return $subject;
    }

    public function update(Subject $subject, Request $request)
    {
        $request->validate([
            "name" => "required",
            "description" => "required",
        ]);

        $subject->update([
            "name" => $request->name,
            "description" => $request->description,
        ]);

        return $subject;
    }

    public function delete(Subject $subject)
    {
        if($subject->class_details()->count() > 0)
            return ["error" => "This subject belongs to a class"];

        return $subject->delete();
    }
}
