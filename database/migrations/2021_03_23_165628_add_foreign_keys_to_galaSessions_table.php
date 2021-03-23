<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGalaSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galaSessions', function (Blueprint $table) {
            $table->foreign('Gala', 'galaSessions_ibfk_1')->references('GalaID')->on('galas')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('galaSessions', function (Blueprint $table) {
            $table->dropForeign('galaSessions_ibfk_1');
        });
    }
}
