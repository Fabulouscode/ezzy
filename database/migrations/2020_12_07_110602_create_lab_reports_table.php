<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_reports', function (Blueprint $table) {
            $table->bigIncrements('id');             
            $table->bigInteger('client_id')->unsigned()->signed();        
            $table->string('report_name');      
            $table->bigInteger('user_id')->unsigned()->signed()->nullable();   
            $table->string('doctor_name')->nullable();
            $table->date('report_date')->nullable();
            $table->time('report_time')->nullable();
            $table->text('description')->nullable();
            $table->longtext('report_images')->nullable();
            $table->timestamps();

           // Foregin Key add
            $table->foreign('client_id')
                ->references('id')
                ->on('users');
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
        Schema::dropIfExists('lab_reports');
    }
}
