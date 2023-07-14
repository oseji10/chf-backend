<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFundAllocationColumnToCoeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coe', function (Blueprint $table) {
            //
            $table->double('fund_allocation')->after('bank_name')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coe', function (Blueprint $table) {
            //
            $table->dropColumn('fund_allocation');
        });
    }
}
