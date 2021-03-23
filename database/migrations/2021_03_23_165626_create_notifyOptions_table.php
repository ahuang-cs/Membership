<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifyOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifyOptions', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('UserID')->index('UserID');
            $table->mediumText('EmailType');
            $table->tinyInteger('Subscribed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifyOptions');
    }
}
