<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('identification_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('identification_number');
            $table->text('identification_document')->nullable();
            $table->string('phone_no_alt')->nullable();
            $table->unsignedBigInteger('ailment_id')->nullable();
            $table->unsignedBigInteger('coe_id');
            $table->double('yearly_income')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('patient');
    }
}
