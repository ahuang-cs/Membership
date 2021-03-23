<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperUsersLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('superUsersLogins', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()')->primary();
            $table->char('User', 36)->index('User');
            $table->timestamp('Time')->useCurrent();
            $table->string('IPAddress', 256);
            $table->string('GeoLocation', 512)->nullable();
            $table->string('Browser', 256);
            $table->string('Platform', 256);
            $table->tinyInteger('Mobile')->default(0);
            $table->string('Hash', 512);
            $table->tinyInteger('HashActive')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('superUsersLogins');
    }
}
