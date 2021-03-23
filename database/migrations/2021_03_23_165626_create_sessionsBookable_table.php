<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsBookableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessionsBookable', function (Blueprint $table) {
            $table->integer('Session');
            $table->date('Date');
            $table->integer('MaxPlaces')->nullable();
            $table->tinyInteger('AllSquads')->nullable()->default(0);
            $table->tinyInteger('RegisterGenerated')->nullable()->default(0);
            $table->dateTime('BookingOpens')->nullable();
            $table->integer('BookingFee')->nullable()->default(0);
            $table->primary(['Session', 'Date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessionsBookable');
    }
}
