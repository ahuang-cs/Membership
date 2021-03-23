<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSquadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squads', function (Blueprint $table) {
            $table->integer('SquadID', true);
            $table->string('SquadName')->nullable();
            $table->decimal('SquadFee')->nullable();
            $table->string('SquadCoach', 100);
            $table->string('SquadTimetable', 100);
            $table->string('SquadCoC', 100);
            $table->string('SquadKey', 20);
            $table->integer('Tenant')->default(1)->index('squads_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('squads');
    }
}
