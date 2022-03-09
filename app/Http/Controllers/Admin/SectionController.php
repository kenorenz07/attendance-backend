<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        return Section::all();
    }

    public function getAll(Request $request)
    {
        $sections = Section::query();

        $per_page = $request->query('per_page') ? $request->query('per_page') : 10;
        $sortBy = $request->query('sortBy');
        $sortDesc = $request->query('sortDesc');

        if($request->query('search_key')){
            $sections
                ->where("name",'LIKE', "%".$request->query('search_key')."%");
        }

        if($sortBy){
            foreach($sortBy as $key => $sort){
                if($sortDesc[$key] == "true"){
                    $sections->orderByDesc($sort);
                }
                else{
                    $sections->orderBy($sort);
                }
            }
        }

        return $sections->paginate($per_page);
    }

    public function show(Section $section) 
    {
        return $section->load('class_details');
    }

    public function classess(Section $section)
    {
        return $section->class_details()->paginate(6);
    }

    public function create(Request $request)
    {
        $request->validate([
            "name" => "required",
        ]);

        $section = Section::create([
            "name" => $request->name,
        ]);

        return $section;
    }

    public function update(Section $section,Request $request)
    {
        $request->validate([
            "name" => "required",
        ]);

        $section->update([
            "name" => $request->name,
        ]);

        return $section;
    }

    public function delete(Section $section)
    {
        if($section->class_details()->count() > 0)
            return ["error" => "This section belongs to a class"];

        return $section->delete();
    }
}
