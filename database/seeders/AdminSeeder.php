<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($int = 0;$int <= 20;$int++){
            Admin::create([
                "name" => "Admin is".$int,
                "username" => "admin_".$int,
                "password" => bcrypt(123123),
                "is_super" => $int % 2,
            ]);
        }
    }
}
