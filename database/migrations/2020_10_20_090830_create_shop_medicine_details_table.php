<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopMedicineDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_medicine_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('medicine_category_id')->unsigned();
            $table->bigInteger('medicine_subcategoy_id')->unsigned();
            $table->bigInteger('medicine_detail_id')->unsigned();
            $table->integer('capsual_quantity')->nullable();            
            $table->string('shirap_ml')->nullable();                
            $table->float('mrp_price')->default(0);
            $table->float('offer_price')->default(0);
            $table->integer('medicine_type')->signed()->default(0)->comment('0-Capsules, 1-Bottle');
            $table->integer('status')->signed()->default(0)->comment('0-Active, 1-Inactive');
            $table->timestamps();
            $table->softDeletes();


            // Foregin Key add
             $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('medicine_category_id')
                  ->references('id')
                  ->on('medicine_categories');
            $table->foreign('medicine_subcategoy_id')
                  ->references('id')
                  ->on('medicine_subcategories');
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
        Schema::dropIfExists('shop_medicine_details');
    }
}
