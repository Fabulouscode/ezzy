<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationManage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign('notifications_receiver_id_foreign');
            $table->bigInteger('receiver_id')->nullable()->unsigned()->change();
            $table->foreign('receiver_id')->references('id')->on('users');

            $table->integer('is_admin_send')->default(0)->after('msg_type')->comment('0-normal, 1-Admin Send');
            $table->integer('general_notification_type')->default(0)->after('is_admin_send')->comment('0-normal, 1-Admin Send type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['is_admin_send','general_notification_type']);
        });
    }
}
