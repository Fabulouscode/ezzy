<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->comment('credit');            
            $table->datetime('transaction_date')->nullable();
            $table->double('amount')->default(0);
            $table->integer('mode_of_payment')->signed()->default(0)->comment('0-debit, 1-credit');
            $table->integer('transaction_type')->signed()->default(0)->comment('0-Wallet,1-Net Banking,2-Debit/Credit Card,3-Paypal');
            $table->integer('status')->signed()->default(0)->comment('0-Success, 1-Unsuccess, 2-Pending');
            $table->text('payment_gateway_response')->nullable();
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
        Schema::dropIfExists('user_transactions');
    }
}
