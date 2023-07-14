<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDfaApprovalColumnToTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction', function (Blueprint $table) {
            //
            $table->integer('dfa_id')->after('payment_approved_on')->nullable();
            $table->timestamp('dfa_approved_on')->after('payment_approved_on')->nullable();
            $table->integer('permsec_id')->after('payment_approved_on')->nullable();
            $table->timestamp('permsec_approved_on')->after('payment_approved_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction', function (Blueprint $table) {
            //
            $table->dropColumn(['dfa_id','dfa_approved_on','permsec_id','permsec_approved_on']);
        });
    }
}
