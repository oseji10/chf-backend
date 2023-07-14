<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropReasonColumnFromApplicationReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_review', function (Blueprint $table) {
            //
            $table->dropColumn('reason');
            $table->dropColumn('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_review', function (Blueprint $table) {
            //
            $table->string('reason');
            $table->integer('reviewed_by');
        });
    }
}
