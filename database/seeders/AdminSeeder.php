<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use Carbon\Carbon as Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->truncate();
       
         $data = [
                    'id'         => 1,
                    'name'       => 'Super Admin',
                    'email'      => 'super@admin.com',
                    'password'   => bcrypt('secret123'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

        Admin::create($data);
    }
}
