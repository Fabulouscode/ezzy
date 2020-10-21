<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('medicine_detail_id')->unsigned();
            $table->bigInteger('shop_medicine_detail_id')->unsigned();
            $table->integer('quantity')->default(0);
            $table->timestamps();
            $table->softDeletes();

             // Foregin Key add
            $table->foreign('medicine_detail_id')
                  ->references('id')
                  ->on('medicine_details');
            $table->foreign('shop_medicine_detail_id')
                  ->references('id')
                  ->on('shop_medicine_details');
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}
