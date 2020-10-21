<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebitTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debit_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->float('debit')->default(0);
            $table->datetime('transaction_date')->nullable();
            $table->text('payment_gateway_response')->nullable();
            $table->integer('status')->signed()->default(0)->comment('0-Success, 1-Unsuccess');
            $table->timestamps();
            $table->softDeletes();


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
        Schema::dropIfExists('debit_transactions');
    }
}
