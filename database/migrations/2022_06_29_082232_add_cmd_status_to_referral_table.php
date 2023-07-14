<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCmdStatusToReferralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral', function (Blueprint $table) {
            //
            $table->timestamp('cmd_approved_on')->nullable();
            $table->unsignedBigInteger('cmd_id')->nullable();
            $table->text('referral_note')->nullable();
            $table->text('appointment_note')->nullable();
            $table->text('fulfill_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referral', function (Blueprint $table) {
            //
            $table->dropColumn(['cmd_approved_on', 'cmd_id', 'referral_note', 'appointment_note', 'fulfill_note',]);
        });
    }
}
