<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountDetailsColumnToCoeTable extends Migration
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
            $table->string('bank_name')->after('admin_id')->nullable();
            $table->string('bank_account_name')->after('admin_id')->nullable();
            $table->string('bank_account_number')->after('admin_id')->nullable();
            $table->string('bank_sort_code')->after('admin_id')->nullable();
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
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_account_name');
            $table->dropColumn('bank_account_number');
        });
    }
}
