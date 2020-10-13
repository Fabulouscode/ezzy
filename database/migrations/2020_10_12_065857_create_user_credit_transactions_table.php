<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCreditTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_credit_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('appointment_id')->unsigned();
            $table->float('credit')->default(0);
            $table->datetime('transaction_date')->nullable();
            $table->text('payment_gateway_response')->nullable();
            $table->integer('status')->signed()->default(0)->comment('0-Unsuccess, 1-Success');
            $table->timestamps();

            // Foregin Key add
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('appointment_id')
                  ->references('id')
                  ->on('appointments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_credit_transactions');
    }
}
