<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Admin::create([
            'username' => 'admin_test',
            'name' => 'John Doe',
            'password' => bcrypt(123123),
            'is_super' => true
        ]);
        
        $this->call(AdminSeeder::class);
        Artisan::call('passport:install');
        Artisan::call('storage:link');
        Artisan::call('key:generate');
    }
}
