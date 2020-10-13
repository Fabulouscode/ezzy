<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foregin Key add
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('categories');
        });

        Schema::table('users', function (Blueprint $table) {
            // Foregin Key add
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories');
            $table->foreign('subcategory_id')
                  ->references('id')
                  ->on('categories');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
