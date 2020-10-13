<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_educations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('college_name')->nullable();
            $table->string('degree_name')->nullable();
            $table->integer('start_year')->signed()->nullable();
            $table->integer('end_year')->signed()->nullable();
            $table->boolean('currently_work')->default(0)->comment('0-No, 1-Yes');
            $table->timestamps();

            // Foregin Key add
            $table->foreign('user_id')
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
        Schema::dropIfExists('user_educations');
    }
}
