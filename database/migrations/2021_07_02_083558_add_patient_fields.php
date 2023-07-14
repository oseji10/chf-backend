<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPatientFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Add new columns to migration table
        Schema::table('patient', function (Blueprint $table) {
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('lga_id')->nullable();
            $table->string('address')->nullable();
            $table->unsignedInteger('state_of_residence')->nullable();
            $table->string('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Droping the columns created
        Schema::table('patient',function(Blueprint $table){
            $table->dropColumn(['state_id','lga_id','address','city','state_of_residence']);
        });
    }
}
