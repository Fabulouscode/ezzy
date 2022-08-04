<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->bigInteger('user_id')->unsigned()->comment('patient');
            $table->bigInteger('shop_medicine_detail_id')->unsigned();
            $table->integer('quantity')->default(0);
            $table->timestamps();

             // Foregin Key add
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
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
        Schema::dropIfExists('shopping_carts');
    }
}
