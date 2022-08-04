<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');    
            $table->bigInteger('permission_category_id')->unsigned()->signed()->nullable();      
            $table->string('permission_title');        
            $table->string('permission_name');        
            $table->timestamps();

                   // Foregin Key add
            $table->foreign('permission_category_id')
                ->references('id')
                ->on('permission_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
