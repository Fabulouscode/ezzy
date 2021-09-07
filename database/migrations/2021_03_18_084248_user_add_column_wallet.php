<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserAddColumnWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {  
           $table->double('lock_wallet_balance')->after('wallet_balance')->default(0);          
           $table->string('user_timezone')->after('lock_wallet_balance')->nullable();       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lock_wallet_balance','user_timezone']);
        });
    }
}
