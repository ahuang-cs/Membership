<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListSendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listSenders', function (Blueprint $table) {
            $table->integer('User');
            $table->integer('List')->index('List');
            $table->tinyInteger('Manager');
            $table->primary(['User', 'List']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listSenders');
    }
}
