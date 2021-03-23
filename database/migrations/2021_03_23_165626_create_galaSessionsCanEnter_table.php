<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalaSessionsCanEnterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galaSessionsCanEnter', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('Member')->index('Member');
            $table->integer('Session')->index('Session');
            $table->tinyInteger('CanEnter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('galaSessionsCanEnter');
    }
}
