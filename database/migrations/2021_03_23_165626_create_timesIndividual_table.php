<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesIndividualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesIndividual', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('MemberID');
            $table->integer('Minutes');
            $table->integer('Seconds');
            $table->integer('Hundreds');
            $table->integer('FinaPoints');
            $table->enum('Round', ['Heat', 'Final']);
            $table->date('Date');
            $table->mediumText('Meet');
            $table->mediumText('Venue');
            $table->integer('Club');
            $table->integer('Level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesIndividual');
    }
}
