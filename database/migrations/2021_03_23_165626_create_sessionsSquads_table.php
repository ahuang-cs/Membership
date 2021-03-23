<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsSquadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessionsSquads', function (Blueprint $table) {
            $table->integer('Squad');
            $table->integer('Session')->index('Session');
            $table->tinyInteger('ForAllMembers')->default(1);
            $table->primary(['Squad', 'Session']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessionsSquads');
    }
}
