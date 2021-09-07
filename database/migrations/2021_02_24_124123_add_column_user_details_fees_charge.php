<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUserDetailsFeesCharge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {            
            $table->double('clinic_consultation_charge')->after('fees_minute')->nullable();
            $table->double('home_consultation_charge')->after('clinic_consultation_charge')->nullable();
            $table->double('video_consultation_charge')->after('home_consultation_charge')->nullable();            
            $table->double('nursing_facility_charge_full_day')->after('video_consultation_charge')->nullable();
            $table->double('nursing_home_visit_charge_full_day')->after('nursing_facility_charge_full_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn(['clinic_consultation_charge', 'home_consultation_charge',
                                'video_consultation_charge', 'nursing_facility_charge_full_day',
                                'nursing_home_visit_charge_full_day']);
        });
    }
}
