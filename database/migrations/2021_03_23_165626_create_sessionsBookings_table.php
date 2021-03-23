<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessionsBookings', function (Blueprint $table) {
            $table->integer('Session');
            $table->date('Date');
            $table->integer('Member')->index('Member');
            $table->dateTime('BookedAt')->useCurrent();
            $table->primary(['Session', 'Date', 'Member']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessionsBookings');
    }
}
