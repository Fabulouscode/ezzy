<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCreditDebitForAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('appointments', function (Blueprint $table) {
            $table->bigInteger('credit_transaction_id')->after('status')->nullable()->unsigned();
            $table->bigInteger('debit_transaction_id')->after('credit_transaction_id')->nullable()->unsigned();
            $table->integer('gender')->after('age')->nullable()->comment('0-Male, 1-Female');
            // Foregin Key add
            $table->foreign('credit_transaction_id')
                  ->references('id')
                  ->on('credit_transactions');
            $table->foreign('debit_transaction_id')
                  ->references('id')
                  ->on('debit_transactions');
        });
    }

    /** 
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['credit_transaction_id','debit_transaction_id','gender']);
        });
    }
}
