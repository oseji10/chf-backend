<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoeV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('coe', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->string('coe_id_cap')->nullable();
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
        Schema::table('coe', function (Blueprint $table) {
            $table->dropColumn('coe_id_cap');
            $table->integer('id');
        });
        
    }
}
