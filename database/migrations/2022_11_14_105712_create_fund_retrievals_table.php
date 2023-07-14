<?php

use App\Helpers\CHFConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundRetrievalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_retrieval', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('approved_on')->nullable();
            $table->double('wallet_balance')->comment('How much the patient had at the time of request');
            $table->double('amount_retrieved')->nullable();
            $table->text('comment')->nullable();
            $table->string('status')->default(CHFConstants::$PENDING);
            $table->unsignedBigInteger('coe_id');
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
        Schema::dropIfExists('fund_retrieval');
    }
}
