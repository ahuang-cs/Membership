<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToNotifyAdditionalEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifyAdditionalEmails', function (Blueprint $table) {
            $table->foreign('UserID', 'notifyAdditionalEmails_ibfk_1')->references('UserID')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifyAdditionalEmails', function (Blueprint $table) {
            $table->dropForeign('notifyAdditionalEmails_ibfk_1');
        });
    }
}
