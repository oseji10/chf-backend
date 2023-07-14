<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PatientPrimaryPhysician extends Migration
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
            $table->unsignedBigInteger('primary_physician')->nullable();
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
            $table->dropColumn('primary_physician');
        });
    }
}
