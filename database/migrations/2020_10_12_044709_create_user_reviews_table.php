<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('client_id')->unsigned();
            $table->text('comment')->nullable();
            $table->float('rating')->nullable();
            $table->datetime('review_date')->nullable();
            $table->integer('status')->signed()->default(0)->comment('0-Active, 1-In Active');
            $table->timestamps();

            // Foregin Key add
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('client_id')
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
        Schema::dropIfExists('user_reviews');
    }
}
