<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_chats', function (Blueprint $table) {
            $table->bigIncrements('id');   
            $table->bigInteger('support_request_id')->unsigned()->signed()->nullable();   
            $table->bigInteger('user_id')->unsigned()->signed()->nullable();   
            $table->bigInteger('admin_id')->unsigned()->signed()->nullable();   
            $table->text('message')->nullable();
            $table->timestamps();           

           // foregin key     
           $table->foreign('support_request_id')
                ->references('id')
                ->on('support_requests');
           $table->foreign('user_id')
                ->references('id')
                ->on('users');
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
        Schema::dropIfExists('support_chats');
    }
}
