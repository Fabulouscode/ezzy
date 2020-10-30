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
            $table->bigInteger('user_service_id')->after('debit_transaction_id')->nullable()->unsigned();
            $table->integer('gender')->after('age')->nullable()->comment('0-Male, 1-Female');
            $table->text('consult_notes')->nullable();
            $table->float('user_rating')->nullable();
            $table->text('user_review')->nullable();
            // Foregin Key add
            $table->foreign('credit_transaction_id')
                  ->references('id')
                  ->on('user_transactions');
            $table->foreign('debit_transaction_id')
                  ->references('id')
                  ->on('user_transactions');
            $table->foreign('user_service_id')
                  ->references('id')
                  ->on('user_services');
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
            $table->dropColumn(['credit_transaction_id','debit_transaction_id','user_service_id','gender','consult_notes','user_rating','user_review']);
        });
    }
}
