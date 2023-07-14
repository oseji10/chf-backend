<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMDTCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mdt_comment', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_user_id');
            $table->bigInteger('mdt_user_id');
            $table->text('comment');
            $table->timestamp('visitation_date');
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
        Schema::dropIfExists('m_d_t_comments');
    }
}
