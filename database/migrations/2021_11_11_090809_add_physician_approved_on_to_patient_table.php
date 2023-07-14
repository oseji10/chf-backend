<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhysicianApprovedOnToPatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patient', function (Blueprint $table) {
            //
            $table->timestamp('primary_physician_reviewed_on')->nullable();
            $table->string('primary_physician_status')->default('pending');
            $table->integer('primary_physician_reviewer_id')->nullable();
            $table->timestamp('social_worker_reviewed_on')->nullable();
            $table->float('mdt_recommended_fund',null,null,true)->default(0);
            $table->text('care_plan')->nullable();
            $table->string('social_worker_status')->default('pending');
            $table->integer('social_worker_reviewer_id')->nullable();
            $table->timestamp('cmd_reviewed_on')->nullable();
            $table->string('cmd_review_status')->default('pending');
            $table->integer('cmd_reviewer_id')->nullable()->comment('This should hold the ID of the account that made CMD approval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient', function (Blueprint $table) {
            //
            $table->dropColumn(['primary_physician_reviewed_on','social_worker_reviewed_on','primary_physician_status','social_worker_status','cmd_reviewed_on','cmd_review_status','social_worker_reviewer_id','cmd_reviewer_id','care_plan','primary_physician_reviewer_id','mdt_recommended_fund']);
        });
    }
}
