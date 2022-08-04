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
            $table->bigInteger('transaction_id')->after('status')->nullable()->unsigned();
            $table->bigInteger('user_service_id')->after('transaction_id')->nullable()->unsigned();
            $table->integer('gender')->after('age')->nullable()->comment('0-Male, 1-Female');
            $table->datetime('completed_datetime')->after('status')->nullable();
            $table->integer('urgent')->after('appointment_type')->nullable()->comment('0-Not Urgent, 1-Urgent');
            $table->text('consult_notes')->nullable();
            $table->float('user_rating')->nullable();
            $table->text('user_review')->nullable();
            // Foregin Key add
            $table->foreign('transaction_id')
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
            $table->dropColumn(['transaction_id','urgent','completed_datetime','user_service_id','gender','consult_notes','user_rating','user_review']);
        });
    }
}
