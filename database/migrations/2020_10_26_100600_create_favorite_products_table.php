<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoriteProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('medicine_detail_id')->unsigned();
            $table->bigInteger('shop_medicine_detail_id')->unsigned();
            $table->timestamps();


              // Foregin Key add
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('medicine_detail_id')
                  ->references('id')
                  ->on('medicine_details');
            $table->foreign('shop_medicine_detail_id')
                  ->references('id')
                  ->on('shop_medicine_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorite_products');
    }
}
