<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSessionsBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessionsBookings', function (Blueprint $table) {
            $table->foreign('Session', 'sessionsBookings_ibfk_1')->references('SessionID')->on('sessions')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('Member', 'sessionsBookings_ibfk_2')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessionsBookings', function (Blueprint $table) {
            $table->dropForeign('sessionsBookings_ibfk_1');
            $table->dropForeign('sessionsBookings_ibfk_2');
        });
    }
}
