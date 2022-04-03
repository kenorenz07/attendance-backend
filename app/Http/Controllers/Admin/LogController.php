<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function getAll(Request $request)
    {
        $logs = Log::query();

        $per_page = $request->query('per_page') ? $request->query('per_page') : 10;
        $sortBy = $request->query('sortBy');
        $sortDesc = $request->query('sortDesc');


        if($request->query('search_key')){
            $logs
                ->where("message",'LIKE', "%".$request->query('search_key')."%");
        }

        if($sortBy){
            foreach($sortBy as $key => $sort){
                if($sortDesc[$key] == "true"){
                    $logs->orderByDesc($sort);
                }
                else{
                    $logs->orderBy($sort);
                }
            }
        }

        return $logs->paginate($per_page);
    }
}
