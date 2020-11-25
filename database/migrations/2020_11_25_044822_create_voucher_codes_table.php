<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucherCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_codes', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('voucher_name');        
            $table->string('voucher_code'); 
            $table->text('description')->nullable();          
            $table->integer('quantity')->default(0);   
            $table->datetime('expiry_date')->nullable();   
            $table->float('percentage')->nullable();   
            $table->float('fix_amount')->nullable();   
            $table->float('min_amount')->nullable();  
            $table->integer('voucher_type')->signed()->nullable()->comment('0-Common, 1-Appointment, 2-Order'); 
            $table->integer('status')->signed()->default(0)->comment('0-active, 1-inactive'); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_codes');
    }
}
