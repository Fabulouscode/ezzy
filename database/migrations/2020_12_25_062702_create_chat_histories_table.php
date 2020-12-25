<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_histories', function (Blueprint $table) {
            $table->bigIncrements('id');  
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('client_id')->unsigned()->comment('patient'); 
            $table->integer('chat_type')->signed()->default(0)->comment('0-ePrescibe, 1-eRecommendation, 2-eDiagnostics, 3-Treatment_plan'); 
            $table->string('plan_name')->nullable(); 
            $table->string('treatment_name')->nullable(); 
            $table->timestamps();


            
            // Foregin Key add
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('client_id')
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
        Schema::dropIfExists('chat_histories');
    }
}
