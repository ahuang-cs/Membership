<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToListSendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listSenders', function (Blueprint $table) {
            $table->foreign('User', 'listSenders_ibfk_1')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('List', 'listSenders_ibfk_2')->references('ID')->on('targetedLists')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listSenders', function (Blueprint $table) {
            $table->dropForeign('listSenders_ibfk_1');
            $table->dropForeign('listSenders_ibfk_2');
        });
    }
}
