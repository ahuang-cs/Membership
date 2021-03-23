<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessionsVenues', function (Blueprint $table) {
            $table->integer('VenueID', true);
            $table->string('VenueName', 100);
            $table->mediumText('Location')->nullable();
            $table->integer('Tenant')->default(1)->index('sv_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessionsVenues');
    }
}
