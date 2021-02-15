<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ColumnAddUserTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {  
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->integer('wallet_transaction')->after('transaction_type')->signed()->default(0)->comment('0-Payment Pay, 1-Add Wallet');
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
            $table->dropColumn(['wallet_transaction']);
        });
    }
}
