<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicineImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('medicine_detail_id')->unsigned();
            $table->text('product_image')->nullable();
            $table->integer('sequence_no')->signed()->nullable();
            $table->timestamps();

                   // Foregin Key add
            $table->foreign('medicine_detail_id')
                  ->references('id')
                  ->on('medicine_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicine_images');
    }
}
