<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSquadMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squadMoves', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('Member');
            $table->date('Date');
            $table->integer('Old')->nullable();
            $table->integer('New')->nullable();
            $table->tinyInteger('Paying')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('squadMoves');
    }
}
