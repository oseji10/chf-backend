<?php

use App\Helpers\CHFConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescription_product', function (Blueprint $table) {
            $table->id();
            $table->integer('prescription_id');
            $table->string('drug_id');
            $table->string('dosage');
            $table->integer('quantity_dispensed')->nullable();
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
        Schema::dropIfExists('prescription_products');
    }
}
