<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSupportRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support_requests', function (Blueprint $table) {
           $table->text('comment')->nullable();
           $table->datetime('closed_date')->nullable();
           $table->string('closed_by')->nullable();
           $table->bigInteger('admin_id')->unsigned()->signed()->nullable();   

           // foregin key     
           $table->foreign('admin_id')
                ->references('id')
                ->on('admins');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('support_requests', function (Blueprint $table) {
            $table->dropColumn(['comment','closed_date','closed_by','admin_id']);
        });
    }
}
