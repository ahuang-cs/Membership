<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetResults', function (Blueprint $table) {
            $table->integer('Result', true);
            $table->integer('Meet')->index('Meet');
            $table->date('Date');
            $table->string('Time', 8);
            $table->integer('IntTime');
            $table->integer('ChronologicalOrder');
            $table->char('Round', 1);
            $table->char('Stroke', 1);
            $table->integer('Distance');
            $table->char('Course', 1);
            $table->integer('Member')->index('FK_MemberID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetResults');
    }
}
