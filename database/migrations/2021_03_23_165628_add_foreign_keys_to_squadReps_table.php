<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSquadRepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('squadReps', function (Blueprint $table) {
            $table->foreign('User', 'squadReps_ibfk_1')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('Squad', 'squadReps_ibfk_2')->references('SquadID')->on('squads')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('squadReps', function (Blueprint $table) {
            $table->dropForeign('squadReps_ibfk_1');
            $table->dropForeign('squadReps_ibfk_2');
        });
    }
}
