<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdentificationLengthToIdentificationDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('identification_document', function (Blueprint $table) {
            //
            $table->integer('identification_length')->after('identification_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('identification_document', function (Blueprint $table) {
            //
            $table->dropColumn('identification_length');
        });
    }
}
