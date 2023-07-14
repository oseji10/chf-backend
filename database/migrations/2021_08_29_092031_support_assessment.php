<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SupportAssessment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('support_assessment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');
            $table->string('feeding_assistance');
            $table->string('medical_assistance');
            $table->string('rent_assistance');
            $table->string('clothing_assistance');
            $table->string('transport_assistance');
            $table->string('mobile_bill_assistance');
            $table->string('status');
            $table->string('sys_feeding_assistance');
            $table->string('sys_medical_assistance');
            $table->string('sys_rent_assistance');
            $table->string('sys_clothing_assistance');
            $table->string('sys_transport_assistance');
            $table->string('sys_mobile_bill_assistance');
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
        Schema::dropIfExists('support_assessment');
    }
}
