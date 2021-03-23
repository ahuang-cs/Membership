<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renewalPeriods', function (Blueprint $table) {
            $table->char('ID', 36)->primary();
            $table->date('Opens');
            $table->longText('Closes');
            $table->string('Name', 256);
            $table->integer('Year');
            $table->integer('Tenant')->default(1)->index('Tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('renewalPeriods');
    }
}
