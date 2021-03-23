<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessionsAttendance', function (Blueprint $table) {
            $table->integer('WeekID')->index('WeekID');
            $table->integer('SessionID')->index('SessionID_2');
            $table->integer('MemberID')->index('MemberID_2');
            $table->integer('AttendanceBoolean')->nullable();
            $table->tinyInteger('AttendanceRequired')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessionsAttendance');
    }
}
