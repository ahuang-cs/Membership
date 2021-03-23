<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renewalProgress', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('UserID');
            $table->integer('RenewalID');
            $table->date('Date');
            $table->integer('Stage');
            $table->integer('Substage');
            $table->integer('Part');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('renewalProgress');
    }
}
