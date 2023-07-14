<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->string('payment_reference')->nullable();
            $table->integer('payment_initiated_by');
            $table->timestamp('payment_initiated_on');
            $table->integer('payment_recommended_by')->nullable();
            $table->timestamp('payment_recommended_on')->nullable();
            $table->integer('payment_approved_by')->nullable();
            $table->timestamp('payment_approved_on')->nullable();
            $table->timestamp('start_date')->nullable()->comment('Start date of services to be paid');
            $table->timestamp('end_date')->nullable()->comment('End date of services to be paid');
            $table->double('payment_amount')->nullable();
            $table->string('status')->default('initiated');
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
        Schema::dropIfExists('payment');
    }
}
