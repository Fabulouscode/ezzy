<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_trackings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_type')->signed()->default('0')->comment('0-User,1-Admin');
            $table->bigInteger('admin_id')->nullable()->unsigned();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->string('field_name')->nullable();
            $table->string('field_value')->nullable();
            $table->timestamps();

            // Foregin Key add
            $table->foreign('admin_id')
                ->references('id')
                ->on('admins');
            $table->foreign('user_id')
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
        Schema::dropIfExists('user_trackings');
    }
}
