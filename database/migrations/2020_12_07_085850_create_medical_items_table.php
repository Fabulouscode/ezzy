<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_items', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('medical_item_name');     
            $table->bigInteger('medical_category_id')->unsigned()->signed();         
            $table->integer('status')->signed()->default(0)->comment('0-active, 1-inactive'); 
            $table->timestamps();
            $table->softDeletes();

           // Foregin Key add
            $table->foreign('medical_category_id')
                ->references('id')
                ->on('medical_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_items');
    }
}
