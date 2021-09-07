<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->bigInteger('subcategory_id')->unsigned()->nullable();
            $table->string('ezzycare_card')->nullable()->unique();    
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('country_code')->nullable();
            $table->string('mobile_no');
            $table->timestamp('mobile_verified_at')->nullable();
            $table->integer('gender')->default(0)->comment('0-Male, 1-Female');
            $table->string('password')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('otp_code')->nullable();
            $table->integer('device_type')->nullable()->signed()->comment('0-IOS, 1-Android');
            $table->string('device_uuid')->nullable();
            $table->string('device_token')->nullable();
            $table->integer('social_type')->nullable()->signed()->comment('0-Facebook, 1-Google, 2-Apple');
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('apple_id')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->double('wallet_balance')->default(0);
            $table->integer('status')->default(0)->signed()->comment('0-Active, 1-Inactive');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
