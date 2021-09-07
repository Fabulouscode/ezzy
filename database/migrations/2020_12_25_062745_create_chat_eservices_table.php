<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatEservicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_eservices', function (Blueprint $table) {
            $table->bigIncrements('id');   
            $table->bigInteger('chat_history_id')->unsigned();
            $table->bigInteger('shop_medicine_detail_id')->unsigned()->nullable();   
            $table->string('medicine_name')->nullable(); 
            $table->integer('quanity')->nullable(); 
            $table->double('price')->nullable(); 
            $table->date('effective_date')->nullable(); 
            $table->text('patient_direction')->nullable(); 
            $table->string('dispense')->nullable(); 
            $table->string('dispense_unit')->nullable(); 
            $table->string('refills')->nullable(); 
            $table->string('days_supply')->nullable();             
            $table->bigInteger('user_service_id')->unsigned()->nullable();            	
            $table->timestamps();


             // Foregin Key add
            $table->foreign('chat_history_id')
                  ->references('id')
                  ->on('chat_histories');
            $table->foreign('shop_medicine_detail_id')
                  ->references('id')
                  ->on('shop_medicine_details');
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
        Schema::dropIfExists('chat_eservices');
    }
}
