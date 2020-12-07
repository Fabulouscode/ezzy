<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayoutColumnUserTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->bigInteger('client_id')->after('user_id')->unsigned()->index()->nullable()->comment('debit');   
            $table->integer('payout_status')->after('status')->signed()->default(1)->comment('0-Paid, 1-Pending, 2-Cancel');
            $table->float('payout_amount')->default(0);
            $table->float('fees_charge')->default(0);
            $table->datetime('payout_date')->nullable();

            // Foregin Key add
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
        Schema::table('user_transactions', function (Blueprint $table) {
             $table->dropColumn(['client_id','payout_amount','fees_charge','payout_date','payout_status']);
        });

    }
}
