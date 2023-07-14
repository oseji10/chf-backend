<?php

use App\Helpers\CHFConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTopupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_topup', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_user_id');
            $table->bigInteger('requester_id');
            $table->timestamp('requested_on')->default(now(CHFConstants::$DEFAULT_TIMEZONE));
            $table->double("amount_requested");
            $table->bigInteger('approver_id')->comment("CMD")->nullable();
            $table->timestamp('approved_on')->nullable();
            $table->bigInteger('credited_by')->nullable();
            $table->timestamp('credited_on')->nullable();
            $table->double('amount_credited')->nullable();
            $table->double("previous_balance");
            $table->text('requester_comment')->nullable();
            $table->string('status')->default(CHFConstants::$INITIATED);
            $table->bigInteger('coe_id');
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
        Schema::dropIfExists('wallet_topup');
    }
}
