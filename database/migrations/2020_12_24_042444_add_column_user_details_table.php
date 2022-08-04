<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
           $table->string('emergency_contact_name')->after('emergency_contact')->nullable();
           $table->string('fees_minute')->after('fees_day')->nullable();
        });
        Schema::table('order_trackings', function (Blueprint $table) {
           $table->datetime('estimation_datetime')->after('status')->nullable();
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
            $table->dropColumn(['emergency_contact_name','fees_minute']);
        });
        Schema::table('order_trackings', function (Blueprint $table) {
            $table->dropColumn(['estimation_datetime']);
        });
    }
}
