<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_amounts', function (Blueprint $table) {
            $table->bigIncrements('id');             
            $table->bigInteger('user_id')->unsigned()->signed();        
            $table->bigInteger('user_bank_account_id')->unsigned()->signed()->nullable();    
            $table->double('amount')->default(0);
            $table->double('deduction_amount')->default(0);
            $table->double('payable_amount')->default(0);
            $table->text('notes')->nullable();
            $table->text('bank_transaction_id')->nullable();
            $table->string('approved_by')->nullable();
            $table->datetime('approved_date')->nullable();
            $table->bigInteger('admin_id')->unsigned()->signed()->nullable();   
            $table->timestamps();

           // Foregin Key add
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreign('user_bank_account_id')
                ->references('id')
                ->on('user_bank_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payout_amounts');
    }
}
