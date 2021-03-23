<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGalaSessionsCanEnterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galaSessionsCanEnter', function (Blueprint $table) {
            $table->foreign('Member', 'galaSessionsCanEnter_ibfk_1')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('Session', 'galaSessionsCanEnter_ibfk_2')->references('ID')->on('galaSessions')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('galaSessionsCanEnter', function (Blueprint $table) {
            $table->dropForeign('galaSessionsCanEnter_ibfk_1');
            $table->dropForeign('galaSessionsCanEnter_ibfk_2');
        });
    }
}
