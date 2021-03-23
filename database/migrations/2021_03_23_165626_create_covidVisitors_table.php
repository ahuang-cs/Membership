<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covidVisitors', function (Blueprint $table) {
            $table->char('ID', 36)->primary();
            $table->char('Location', 36)->index('Location');
            $table->dateTime('Time');
            $table->integer('Person')->nullable();
            $table->enum('Type', ['member', 'user', 'guest']);
            $table->string('GuestName', 256)->nullable();
            $table->string('GuestPhone', 256)->nullable();
            $table->integer('Inputter')->nullable()->index('Inputter');
            $table->text('Notes')->nullable();
            $table->tinyInteger('SignedOut')->default(0);
            $table->integer('Tenant')->index('Tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('covidVisitors');
    }
}
