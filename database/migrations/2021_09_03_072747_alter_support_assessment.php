<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSupportAssessment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('support_assessment', function (Blueprint $table) {
            $table->dropColumn('sys_feeding_assistance');
            $table->dropColumn('sys_medical_assistance');
            $table->dropColumn('sys_rent_assistance');
            $table->dropColumn('sys_clothing_assistance');
            $table->dropColumn('sys_transport_assistance');
            $table->dropColumn('sys_mobile_bill_assistance');
            $table->unsignedInteger('points_user_input');
            $table->unsignedInteger('points_sys_suggested');
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
        Schema::table('support_assessment', function (Blueprint $table) {
            $table->string('sys_feeding_assistance');
            $table->string('sys_medical_assistance');
            $table->string('sys_rent_assistance');
            $table->string('sys_clothing_assistance');
            $table->string('sys_transport_assistance');
            $table->string('sys_mobile_bill_assistance');
            $table->dropColumn('points_user_input');
            $table->dropColumn('points_sys_suggested');
        });
    }
}
