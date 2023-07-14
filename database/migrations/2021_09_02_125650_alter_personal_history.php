<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPersonalHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('personal_history', function (Blueprint $table) {
            $table->dropColumn('average_income_per_month');
            $table->dropColumn('average_eat_daily');
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
        Schema::table('personal_history', function (Blueprint $table) {
            $table->unsignedBigInteger('average_income_per_month');
            $table->unsignedBigInteger('average_eat_daily');
        });
    }
}
