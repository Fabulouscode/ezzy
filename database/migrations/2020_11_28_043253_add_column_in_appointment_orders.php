<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInAppointmentOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('full_day')->after('status')->default(0)->comment('0-No, 1-Yes');
            $table->bigInteger('voucher_code_id')->after('full_day')->unsigned()->index()->nullable();
            $table->double('voucher_amount')->after('voucher_code_id')->nullable();
            $table->double('hcp_fees')->after('voucher_amount')->nullable();
            $table->double('home_visit_fees')->after('hcp_fees')->nullable();
            $table->double('total_charge')->after('home_visit_fees')->nullable();

              // Foregin Key add
            $table->foreign('voucher_code_id')
                  ->references('id')
                  ->on('voucher_codes');
        });

        Schema::table('appointment_services', function (Blueprint $table) {
            $table->double('service_price')->after('user_service_id')->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('voucher_code_id')->after('status')->unsigned()->index()->nullable();
            $table->double('voucher_amount')->after('voucher_code_id')->nullable();

              // Foregin Key add
            $table->foreign('voucher_code_id')
                  ->references('id')
                  ->on('voucher_codes');
        });

        Schema::table('order_products', function (Blueprint $table) {
            $table->double('medicine_price')->after('quantity')->nullable();
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
             $table->dropColumn(['voucher_code_id','voucher_amount','hcp_fees','home_visit_fees','total_charge']);
        });

        Schema::table('appointment_services', function (Blueprint $table) {
           $table->dropColumn(['service_price']);
        });

        Schema::table('orders', function (Blueprint $table) {
           $table->dropColumn(['voucher_code_id','voucher_amount']);
        });
        
        Schema::table('order_products', function (Blueprint $table) {
           $table->dropColumn(['medicine_price']);
        });
    }
}
