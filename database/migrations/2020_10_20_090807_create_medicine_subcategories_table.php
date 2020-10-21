<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicineSubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine_subcategories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('medicine_category_id')->unsigned();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('status')->signed()->default(0)->comment('0-Active, 1-Inactive');
            $table->timestamps();
            $table->softDeletes();


            // Foregin Key add
            $table->foreign('medicine_category_id')
                  ->references('id')
                  ->on('medicine_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicine_subcategories');
    }
}
