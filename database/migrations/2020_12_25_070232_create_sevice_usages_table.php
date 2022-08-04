<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeviceUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sevice_usages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name'); 
            $table->timestamps();
        });
        
        Schema::table('services', function (Blueprint $table) {
           $table->text('sevice_usages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sevice_usages');
        
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['sevice_usages']);
        });
    }
}
