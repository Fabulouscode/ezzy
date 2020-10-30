<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sender_id')->unsigned()->signed()->nullable();            
            $table->bigInteger('receiver_id')->unsigned();            
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->longtext('parameter')->nullable();
            $table->integer('msg_type')->signed()->default(0);
            $table->integer('read')->signed()->default(0)->comment('0-Read, 1-Unread');
            $table->timestamps();
            $table->softDeletes();

               // Foregin Key add
            $table->foreign('sender_id')
                  ->references('id')
                  ->on('users');
            $table->foreign('receiver_id')
                  ->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
