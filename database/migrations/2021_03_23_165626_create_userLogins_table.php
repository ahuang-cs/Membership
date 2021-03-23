<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userLogins', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('UserID')->index('UserID');
            $table->timestamp('Time')->useCurrent();
            $table->mediumText('IPAddress');
            $table->mediumText('GeoLocation')->nullable();
            $table->mediumText('Browser');
            $table->mediumText('Platform');
            $table->tinyInteger('Mobile');
            $table->mediumText('Hash');
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
        Schema::dropIfExists('userLogins');
    }
}
