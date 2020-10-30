<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('appointment_id')->unsigned()->comment('appointment');
            $table->bigInteger('user_service_id')->unsigned();
            $table->timestamps();

               // Foregin Key add
            $table->foreign('appointment_id')
                  ->references('id')
                  ->on('appointments');
            $table->foreign('user_service_id')
                  ->references('id')
                  ->on('user_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_services');
    }
}
