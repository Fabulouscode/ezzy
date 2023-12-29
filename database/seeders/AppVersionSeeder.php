<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\AppVersion;
use Carbon\Carbon as Carbon;

class AppVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('app_versions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
         $data = [
                    'id'         => 1,
                    'android_version' => '0.1',
                    'ios_version'      => '0.1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

            AppVersion::create($data);
    }
}
