<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSquadRepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squadReps', function (Blueprint $table) {
            $table->integer('User');
            $table->integer('Squad')->index('Squad');
            $table->string('ContactDescription')->nullable();
            $table->primary(['User', 'Squad']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('squadReps');
    }
}
