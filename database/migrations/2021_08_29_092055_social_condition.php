<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SocialCondition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('social_condition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');
            $table->string('have_running_water');
            $table->string('type_of_toilet_facility');
            $table->string('have_generator_solar');
            $table->string('means_of_transportation');
            $table->string('have_mobile_phone');
            $table->string('how_maintain_phone_use');
            $table->string('status');
            $table->unsignedBigInteger('application_review_id');
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
        Schema::dropIfExists('social_condition');
    }
}
