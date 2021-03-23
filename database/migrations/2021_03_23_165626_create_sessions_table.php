<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->integer('SessionID', true);
            $table->integer('VenueID')->index('VenueID');
            $table->string('SessionName', 100);
            $table->integer('SessionDay');
            $table->time('StartTime')->nullable();
            $table->time('EndTime')->nullable();
            $table->date('DisplayFrom')->nullable();
            $table->date('DisplayUntil')->nullable();
            $table->integer('Tenant')->default(1)->index('sessions_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
