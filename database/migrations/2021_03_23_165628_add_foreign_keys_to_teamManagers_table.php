<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTeamManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teamManagers', function (Blueprint $table) {
            $table->foreign('User', 'teamManagers_ibfk_1')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('Gala', 'teamManagers_ibfk_2')->references('GalaID')->on('galas')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teamManagers', function (Blueprint $table) {
            $table->dropForeign('teamManagers_ibfk_1');
            $table->dropForeign('teamManagers_ibfk_2');
        });
    }
}
