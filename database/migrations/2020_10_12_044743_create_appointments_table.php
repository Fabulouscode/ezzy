<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('client_id')->unsigned()->comment('patient');
            $table->integer('appointment_type')->signed()->nullable()->comment('0-In Clinic, 1-Home Care, 2-Video Call');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('age')->nullable();
            $table->text('reason')->nullable();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->double('appointment_price')->default(0);
            $table->string('otp_code')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->datetime('cancel_date')->nullable();
            $table->bigInteger('cancel_user_id')->unsigned()->nullable();
            $table->integer('status')->default(0)->signed()->comment('0-Pending, 1-Upcoming, 2-in_progress, 3-Paid, 4-Unpaid, 5-Success, 6-Cancel');
            $table->timestamps();
            $table->softDeletes();


            // Foregin Key add
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('client_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('cancel_user_id')
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
        Schema::dropIfExists('appointments');
    }
}
