<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ColumnAddManageFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manage_fees', function (Blueprint $table) {
            $table->string('fees_name')->after('fees_percentage')->nullable();
            $table->string('fees_key')->after('fees_name')->nullable();
            $table->integer('fees_type')->after('fees_key')->signed()->default(0)->comment('0-Common fees, 1-Category Fees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manage_fees', function (Blueprint $table) {
            $table->dropColumn(['fees_name','fees_key','fees_type']);
        });
    }
}
