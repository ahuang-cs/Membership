<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('superUsers', function (Blueprint $table) {
            $table->char('ID', 36)->primary();
            $table->string('First', 256);
            $table->string('Last', 256);
            $table->string('PWHash', 256);
            $table->string('Email', 256);
            $table->string('TwoFactor', 256);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('superUsers');
    }
}
