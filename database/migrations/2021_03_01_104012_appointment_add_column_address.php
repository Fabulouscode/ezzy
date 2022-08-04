<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppointmentAddColumnAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {   
            $table->string('city')->after('address')->nullable();
            $table->string('country')->after('city')->nullable();
            $table->integer('my_appointment')->after('appointment_time')->signed()->default('0')->comment('0-My, 1-Other');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['city','country','my_appointment']);
        });
    }
}
