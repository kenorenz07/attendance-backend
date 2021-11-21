<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function getAll(Request $request)
    {
        $admins = Admin::query()->where('id','!=',Auth::id());

        $per_page = $request->query('per_page') ? $request->query('per_page') : 10;
        $sortBy = $request->query('sortBy');
        $sortDesc = $request->query('sortDesc');

        if($request->query('search_key')){
            $admins
                ->orWhere("username", 'LIKE', "%".$request->query('search_key')."%")
                ->orWhere("name", 'LIKE', "%".$request->query('search_key')."%");
        }
        if($sortBy){
            foreach($sortBy as $key => $sort){
                if($sortDesc[$key] == "true"){
                    $admins->orderByDesc($sort);
                }
                else{
                    $admins->orderBy($sort);
                }
            }
       }

        return $admins->paginate($per_page);
    }

    public function create(Request $request)
    {
        $validator = $request->validate([
            "name" => 'required',
            "username" => 'required',
            "password" => 'required',
            "is_super" => 'required',
        ]);

        $admin = Admin::create([
            "name" => $request->name,
            "username" => $request->username,
            "password" => bcrypt($request->password),
            "is_super" => $request->is_super,
        ]);


        if(str_contains($request->image,'base64')){
            $fileName = uploadImage("admin_",$request->image);
            
            $admin->image()->create([
                "name" => $fileName
            ]);
        }

        return $admin;
    }

    public function update(Admin $admin,Request $request)
    {
        $update_admin = $admin->update([
            "name" => $request->name,
            "username" => $request->username,
            "password" => $request->password ? bcrypt($request->password): $admin->password,
            "is_super" => $request->is_super,
        ]);

        
        if(str_contains($request->image,'base64')){

            $fileName = uploadImage("admin_",$request->image);
            
            if($admin->image){
                Storage::delete('/public/'.$admin->image->name);
                $admin->image()->delete();
            }
            
            $admin->image()->create([
                "name" => $fileName
            ]);

        }

        return $update_admin;
    }

    public function delete(Admin $admin, Request $request)
    {
        if($admin->image){
            Storage::delete('/public/'.$admin->image->name);
            $admin->image()->delete();
        }
        
        return $admin->delete();
    }
}
