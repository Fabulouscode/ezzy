<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->text('about_us')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->date('dob')->nullable();
            $table->string('normal_fees')->nullable();
            $table->string('urgent_fees')->nullable();
            $table->string('home_visit_fees')->nullable();
            $table->boolean('urgent')->default(0)->comment('0-Not Urgent Available, 1-Urgent Available');
            $table->boolean('availability')->default(0)->comment('0-Availability, 1-Not Available');
            $table->string('registration_no')->nullable();
            $table->string('registration_council')->nullable();
            $table->integer('registration_year')->signed()->nullable();
            $table->string('clinic_name')->nullable();
            $table->string('clinic_city')->nullable();
            $table->string('clinic_locality')->nullable();
            $table->string('total_experiance_year')->nullable();
            $table->boolean('same_timing')->default(0)->comment('0-Same Time Not Available, 1-Same Time Available');
            $table->timestamps();


            // Foregin Key add
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
