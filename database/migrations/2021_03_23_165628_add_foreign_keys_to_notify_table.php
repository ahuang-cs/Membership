<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToNotifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notify', function (Blueprint $table) {
            $table->foreign('MessageID', 'notify_ibfk_1')->references('ID')->on('notifyHistory')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('Sender', 'notify_ibfk_2')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('UserID', 'notify_ibfk_3')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notify', function (Blueprint $table) {
            $table->dropForeign('notify_ibfk_1');
            $table->dropForeign('notify_ibfk_2');
            $table->dropForeign('notify_ibfk_3');
        });
    }
}
