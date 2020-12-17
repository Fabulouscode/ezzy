<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnVideoAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('appointments', function (Blueprint $table) {
           $table->text('address')->nullable();
           $table->time('video_start_time')->nullable();
           $table->time('video_end_time')->nullable();
           $table->string('longitude')->nullable();
           $table->string('latitude')->nullable();

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
            $table->dropColumn(['address','video_start_time','video_end_time','longitude','latitude']);
        });
    }
}
