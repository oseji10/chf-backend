<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalColumnsToTransactionTable extends Migration
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
            $table->integer('payment_initiated_by')->nullable();
            $table->integer('payment_recommended_by')->nullable();
            $table->integer('payment_approved_by')->nullable();
            $table->timestamp('payment_initiated_on')->nullable();
            $table->timestamp('payment_recommended_on')->nullable();
            $table->timestamp('payment_approved_on')->nullable();
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
            $table->dropColumn('payment_initiated_by');
            $table->dropColumn('payment_recommended_by');
            $table->dropColumn('payment_approved_by');
            $table->dropColumn('payment_initiated_on');
            $table->dropColumn('payment_recommended_on');
            $table->dropColumn('payment_approved_on');
        });
    }
}
