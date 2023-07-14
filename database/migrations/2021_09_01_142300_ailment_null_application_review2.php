<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AilmentNullApplicationReview2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('application_review', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('ailment_id')->nullable();
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
        Schema::table('application_review', function (Blueprint $table) {
            //
            $table->dropColumn('ailment_id');
        });
    }
}
