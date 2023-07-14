<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialWorkerAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_worker_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');
            $table->string('appearance');
            $table->string('bmi')->nullable();
            $table->text('comment_on_home')->nullable();
            $table->text('comment_on_environment')->nullable();
            $table->text('comment_on_fammily')->nullable();
            $table->text('general_comment')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('application_review_id');
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
        Schema::dropIfExists('social_worker_assessments');
    }
}
