<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLinkedAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('linkedAccounts', function (Blueprint $table) {
            $table->foreign('User', 'linkedAccounts_ibfk_1')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('LinkedUser', 'linkedAccounts_ibfk_2')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('linkedAccounts', function (Blueprint $table) {
            $table->dropForeign('linkedAccounts_ibfk_1');
            $table->dropForeign('linkedAccounts_ibfk_2');
        });
    }
}
