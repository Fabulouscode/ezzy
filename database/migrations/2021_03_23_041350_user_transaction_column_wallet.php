<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTransactionColumnWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_transactions', function (Blueprint $table) {            
            $table->bigInteger('appointment_id')->after('payment_gateway_response')->unsigned()->index()->nullable();  
            $table->bigInteger('order_id')->after('appointment_id')->unsigned()->index()->nullable();  
            
                // Foregin Key add
            $table->foreign('appointment_id')
                  ->references('id')
                  ->on('appointments');
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->dropColumn(['appointment_id','order_id']);
        });
    }
}
