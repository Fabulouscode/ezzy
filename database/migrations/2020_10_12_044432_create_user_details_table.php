<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('clinic_hospital_name')->nullable();
            $table->text('about_us')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('pincode')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->date('dob')->nullable();
            $table->integer('marital_status')->signed()->nullable()->comment('0-Single, 1-Married');
            $table->string('blood_group')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->text('allergies')->nullable();
            $table->boolean('smoking_habbits')->default(0)->comment('0-No, 1-Yes');
            $table->boolean('alcohole_consumption')->default(0)->comment('0-No, 1-Yes');
            $table->integer('food_preference')->signed()->nullable()->comment('0-Veg., 1-Non Veg.');
            $table->string('occupation')->nullable();
            $table->string('normal_fees')->nullable();
            $table->string('urgent_fees')->nullable();
            $table->string('home_visit_fees')->nullable();
            $table->string('delivery_charge')->nullable();
            $table->boolean('urgent')->default(0)->comment('0-Not Urgent Available, 1-Urgent Available');
            $table->boolean('availability')->default(0)->comment('0-Availability, 1-Not Available');
            $table->string('registration_no')->nullable();
            $table->string('registration_council')->nullable();
            $table->integer('registration_year')->signed()->nullable();
            $table->string('clinic_name')->nullable();
            $table->string('clinic_city')->nullable();
            $table->string('clinic_locality')->nullable();            
            $table->string('total_experiance_year')->nullable();
            $table->text('qualification_certificate')->nullable();
            $table->text('practicing_licence')->nullable();
            $table->text('health_facility_certificate')->nullable();
            $table->text('regstration_certificate')->nullable();
            $table->text('pharmacist_certificate')->nullable();
            $table->integer('qualification_certificate_status')->default(0)->comment('0-Pending, 1-Rejected');
            $table->integer('practicing_licence_status')->default(0)->comment('0-Pending, 1-Rejected');
            $table->integer('health_facility_certificate_status')->default(0)->comment('0-Pending, 1-Rejected');
            $table->integer('regstration_certificate_status')->default(0)->comment('0-Pending, 1-Rejected');
            $table->integer('pharmacist_certificate_status')->default(0)->comment('0-Pending, 1-Rejected');
            $table->boolean('same_timing')->default(0)->comment('0-Same Time Not Available, 1-Same Time Available');
            $table->timestamps();


            // Foregin Key add
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
        Schema::dropIfExists('user_details');
    }
}
