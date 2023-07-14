<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPersonalHistory2 extends Migration
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
            $table->double('average_income_per_month');
            $table->double('average_eat_daily');
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
            $table->dropColumn('average_income_per_month');
            $table->dropColumn('average_eat_daily');
        });
    }
}
