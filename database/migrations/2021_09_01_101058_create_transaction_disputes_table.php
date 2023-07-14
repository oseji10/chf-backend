<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDisputesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_dispute', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('disputed_by');
            $table->unsignedBigInteger('coe_id');
            $table->unsignedBigInteger('patient_user_id');
            $table->unsignedBigInteger('coe_staff_id');
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->timestamp('resolved_on')->nullable();
            $table->text('reason_for_dispute')->nullable();
            $table->text('dispute_resolution')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('transaction_dispute');
    }
}
