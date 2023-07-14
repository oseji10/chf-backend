<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientTransferRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_transfer_request', function (Blueprint $table) {
            $table->id();
            $table->string('patient_chf_id');
            $table->integer('requesting_physician_id');
            $table->integer('current_physician_id')->nullable()->comment('The physician patient is registered to before transfer request');
            $table->integer('approved_by')->nullable();
            $table->timestamp('approved_on')->nullable();
            $table->string('status')->default("PENDING");
            $table->string('reviewer_comment')->comment("Reason for decline or acceptance")->nullable();
            $table->string('reason_for_transfer')->comment("Reason for decline")->nullable();
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
        Schema::dropIfExists('patient_transfer_request');
    }
}
