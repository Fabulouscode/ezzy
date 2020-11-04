<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('client_id')->unsigned()->comment('patient');
            $table->bigInteger('user_location_id')->unsigned()->nullable();
            $table->float('total_price')->default(0);
            $table->float('shipping_price')->default(0);
            $table->text('payment_res')->nullable();
            $table->string('otp_code')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->datetime('cancel_date')->nullable();
            $table->bigInteger('cancel_user_id')->unsigned()->nullable();
            $table->integer('delivery_type')->signed()->default(0)->comment('0-Home Delievry, 1-pick-up from store');
            $table->integer('status')->signed()->default(0)->comment('0-Pending, 1-Success, 2-Cancel');
            $table->datetime('completed_datetime')->nullable();
            $table->float('user_rating')->nullable();
            $table->text('user_review')->nullable();
            $table->bigInteger('credit_transaction_id')->nullable()->unsigned();
            $table->bigInteger('debit_transaction_id')->nullable()->unsigned();
            $table->timestamps();
            $table->softDeletes();
            
            // Foregin Key add
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('client_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('cancel_user_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('user_location_id')
                  ->references('id')
                  ->on('user_locations');
            $table->foreign('credit_transaction_id')
                  ->references('id')
                  ->on('user_transactions');
            $table->foreign('debit_transaction_id')
                  ->references('id')
                  ->on('user_transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
