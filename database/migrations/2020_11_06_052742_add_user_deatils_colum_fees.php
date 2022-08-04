<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserDeatilsColumFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->string('fees_hour')->after('delivery_charge')->nullable();
            $table->string('fees_day')->after('fees_hour')->nullable();
            $table->longText('allergies')->nullable()->change();
            $table->longText('current_medications')->nullable();
            $table->longText('past_medications')->nullable();
            $table->longText('chronic_disease')->nullable();
            $table->longText('injuries')->nullable();
            $table->longText('surgeries')->nullable();
            $table->text('smoking_habbits')->nullable()->change();
            $table->text('alcohole_consumption')->nullable()->change();
            $table->text('activity_level')->nullable();
            $table->text('food_preference')->nullable()->change();
            $table->text('occupation')->nullable()->change();
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
            $table->dropColumn(['fees_hour','fees_day','activity_level',
                                'current_medications','past_medications',
                                'chronic_disease','injuries','surgeries']);
        });
    }
}
