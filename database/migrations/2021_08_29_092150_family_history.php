<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FamilyHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('family_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');
            $table->string('family_set_up');
            $table->unsignedInteger('family_size');
            $table->unsignedInteger('birth_order');
            $table->string('father_education_status');
            $table->string('mother_education_status');
            $table->string('fathers_occupation');
            $table->string('mothers_occupation');
            $table->string('level_of_family_care');
            $table->unsignedInteger('family_total_income_month');
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
        Schema::dropIfExists('family_history');
    }
}
