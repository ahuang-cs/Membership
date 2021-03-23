<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSessionsAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sessionsAttendance', function (Blueprint $table) {
            $table->foreign('WeekID', 'sessionsAttendance_ibfk_1')->references('WeekID')->on('sessionsWeek')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('SessionID', 'sessionsAttendance_ibfk_2')->references('SessionID')->on('sessions')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('MemberID', 'sessionsAttendance_ibfk_3')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessionsAttendance', function (Blueprint $table) {
            $table->dropForeign('sessionsAttendance_ibfk_1');
            $table->dropForeign('sessionsAttendance_ibfk_2');
            $table->dropForeign('sessionsAttendance_ibfk_3');
        });
    }
}
