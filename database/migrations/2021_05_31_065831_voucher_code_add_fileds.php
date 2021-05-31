<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VoucherCodeAddFileds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voucher_codes', function (Blueprint $table) {  
            $table->integer('voucher_used')->signed()->default(0)->after('voucher_type')->comment('0-One time used, 1-Multiple time used'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voucher_codes', function (Blueprint $table) {
            $table->dropColumn(['voucher_used']);
        });
    }
}
