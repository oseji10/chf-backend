<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PersonalHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('personal_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('average_income_per_month');
            $table->unsignedBigInteger('average_eat_daily');
            $table->string('who_provides_feeding');
            $table->string('have_accomodation');
            $table->string('type_of_accomodation');
            $table->unsignedInteger('no_of_good_set_of_cloths');
            $table->string('how_you_get_them');
            $table->string('where_you_receive_care');
            $table->string('why_choose_center_above');
            $table->string('level_of_spousal_support');
            $table->string('other_support');
            $table->string('status');
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
        //
        Schema::dropIfExists('personal_history');
    }
}
