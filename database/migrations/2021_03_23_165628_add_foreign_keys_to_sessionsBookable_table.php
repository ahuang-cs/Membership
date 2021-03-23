<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSessionsBookableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessionsBookable', function (Blueprint $table) {
            $table->foreign('Session', 'sessionsBookable_ibfk_1')->references('SessionID')->on('sessions')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessionsBookable', function (Blueprint $table) {
            $table->dropForeign('sessionsBookable_ibfk_1');
        });
    }
}
