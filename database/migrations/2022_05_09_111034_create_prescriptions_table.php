<?php

use App\Helpers\CHFConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescription', function (Blueprint $table) {
            $table->id();
            $table->integer('coe_id');
            $table->integer('created_by')->comment("Doctor who created the prescription");
            $table->integer('patient_user_id');
            $table->integer('fulfilled_by')->comment("Pharmacist that fullfulled the prescription")->nullable();
            $table->timestamp('fulfilled_on')->nullable();
            $table->text('creator_comment')->nullable();
            $table->text('fulfiller_comment')->nullable();
            $table->string('status')->default(CHFConstants::$PENDING);
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
        Schema::dropIfExists('prescriptions');
    }
}
