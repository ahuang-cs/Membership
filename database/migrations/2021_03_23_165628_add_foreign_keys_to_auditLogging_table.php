<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAuditLoggingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auditLogging', function (Blueprint $table) {
            $table->foreign('User', 'auditLogging_ibfk_1')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auditLogging', function (Blueprint $table) {
            $table->dropForeign('auditLogging_ibfk_1');
        });
    }
}
