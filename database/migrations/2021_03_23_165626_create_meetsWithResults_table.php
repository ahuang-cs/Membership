<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetsWithResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetsWithResults', function (Blueprint $table) {
            $table->integer('Meet', true);
            $table->integer('Gala')->nullable()->index('Gala');
            $table->string('Name', 100);
            $table->string('City', 50);
            $table->date('Start')->nullable();
            $table->date('End')->nullable();
            $table->char('Course', 1);
            $table->integer('Tenant')->default(1)->index('mwr_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetsWithResults');
    }
}
