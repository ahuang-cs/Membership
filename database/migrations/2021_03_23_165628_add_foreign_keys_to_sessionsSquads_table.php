<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSessionsSquadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessionsSquads', function (Blueprint $table) {
            $table->foreign('Squad', 'sessionsSquads_ibfk_1')->references('SquadID')->on('squads')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('Session', 'sessionsSquads_ibfk_2')->references('SessionID')->on('sessions')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessionsSquads', function (Blueprint $table) {
            $table->dropForeign('sessionsSquads_ibfk_1');
            $table->dropForeign('sessionsSquads_ibfk_2');
        });
    }
}
