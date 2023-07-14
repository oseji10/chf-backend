<?php

use App\Helpers\CHFConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_coe_id');
            $table->unsignedBigInteger('referring_coe_id');
            $table->unsignedBigInteger('referred_by');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamp('assigned_on')->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->string('status')->default(CHFConstants::$PENDING);
            $table->timestamp('fulfilled_on')->nullable();
            $table->unsignedBigInteger('fulfilled_by')->nullable();
            $table->string('patient_chf_id');
            $table->dateTime('appointment_date')->nullable();
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
        Schema::dropIfExists('referral');
    }
}
