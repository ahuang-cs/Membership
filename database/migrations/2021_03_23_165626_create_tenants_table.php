<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->string('Name', 128);
            $table->char('Code', 4)->nullable()->unique('Code');
            $table->string('Website', 256)->nullable();
            $table->string('Email', 256);
            $table->tinyInteger('Verified')->default(0);
            $table->string('UniqueID', 36)->default('uuid()');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
