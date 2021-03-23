<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meets', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->string('Name', 200);
            $table->dateTime('StartTime');
            $table->integer('Creator')->index('Creator');
            $table->tinyInteger('Started')->default(0);
            $table->tinyInteger('Finished')->default(0);
            $table->string('Link', 2048);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meets');
    }
}
