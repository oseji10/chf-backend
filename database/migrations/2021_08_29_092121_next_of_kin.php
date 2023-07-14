<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NextOfKin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('next_of_kin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('relationship');
            $table->string('phone_number');
            $table->string('alternate_phone_number')->nullable();
            $table->string('email');
            $table->string('address');
            $table->string('city');
            $table->unsignedInteger('state_of_residence');
            $table->unsignedInteger('lga_of_residence');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('next_of_kin');
    }
}
