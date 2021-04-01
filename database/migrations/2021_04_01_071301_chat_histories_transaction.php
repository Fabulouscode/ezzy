<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChatHistoriesTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_histories', function (Blueprint $table) {            
            $table->bigInteger('transaction_id')->unsigned()->index()->nullable(); 
            
                // Foregin Key add
            $table->foreign('transaction_id')
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
        Schema::table('chat_histories', function (Blueprint $table) {
            $table->dropColumn(['transaction_id']);
        });
    }
}
