<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUIMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ui_menu', function (Blueprint $table) {
            $table->id();
            $table->string('menu_name');
            $table->string('menu_link');
            $table->string('menu_category');
            $table->string('menu_permission');
            $table->integer('menu_parent_id')->nullable();
            $table->string('menu_icon')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('ui_menu');
    }
}
