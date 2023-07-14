<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PersonalInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('personal_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');
            $table->string('nhis_no');
            $table->string('gender');
            $table->unsignedInteger('age');
            $table->string('ethnicity');
            $table->string('marital_status');
            $table->unsignedInteger('no_of_children');
            $table->string('level_of_education');
            $table->string('religion');
            $table->string('occupation');
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
        Schema::dropIfExists('personal_information');
    }
}
