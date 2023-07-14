<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSocialWorkerAssessment2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('social_worker_assessments', function (Blueprint $table) {
            $table->double('bmi')->default(0);
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
        Schema::table('social_worker_assessments', function (Blueprint $table) {
            $table->dropColumn('bmi');
        });
    }
}
