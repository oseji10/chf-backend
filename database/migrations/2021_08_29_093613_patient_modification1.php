<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PatientModification1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('patient', function (Blueprint $table) {
            $table->string('nhis_no');
            $table->string('ethnicity');
            $table->string('marital_status');
            $table->unsignedInteger('no_of_children');
            $table->string('level_of_education');
            $table->string('religion');
            $table->string('occupation');
            $table->dropColumn('yearly_income');
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
        Schema::table('patient', function (Blueprint $table) {
            $table->dropColumn('nhis_no');
            $table->dropColumn('ethnicity');
            $table->dropColumn('marital_status');
            $table->dropColumn('no_of_children');
            $table->dropColumn('level_of_education');
            $table->dropColumn('religion');
            $table->dropColumn('occupation');
            $table->unsignedInteger('yearly_income')->nullable();
        });
    }
}
