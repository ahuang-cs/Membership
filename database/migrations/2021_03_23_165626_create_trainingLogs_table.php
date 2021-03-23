<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainingLogs', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('Member')->index('Member');
            $table->dateTime('DateTime');
            $table->text('Content');
            $table->string('ContentType', 100);
            $table->string('Title', 150);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trainingLogs');
    }
}
