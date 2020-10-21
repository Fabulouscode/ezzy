<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicineDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('medicine_category_id')->unsigned();
            $table->bigInteger('medicine_subcategoy_id')->unsigned();
            $table->string('medicine_name');
            $table->text('medicine_sku')->nullable();
            $table->text('description')->nullable();
            $table->text('medicine_image')->nullable();
            $table->integer('medicine_type')->signed()->default(0)->comment('0-Capsules, 1-Bottle');
            $table->integer('status')->signed()->default(0)->comment('0-Active, 1-Inactive');
            $table->timestamps();
            $table->softDeletes();


            // Foregin Key add
            $table->foreign('medicine_category_id')
                  ->references('id')
                  ->on('medicine_categories');
            $table->foreign('medicine_subcategoy_id')
                  ->references('id')
                  ->on('medicine_subcategories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicine_details');
    }
}
