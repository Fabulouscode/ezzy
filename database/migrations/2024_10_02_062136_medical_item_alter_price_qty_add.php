<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MedicalItemAlterPriceQtyAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicine_details', function (Blueprint $table) {
            $table->integer('mrp_price')->after('medicine_type')->signed()->default(0);
            $table->integer('quantity')->after('mrp_price')->signed()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicine_details', function (Blueprint $table) {
            $table->dropColumn(['mrp_price','quantity']);
        });
    }
}
