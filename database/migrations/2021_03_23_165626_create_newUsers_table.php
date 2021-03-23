<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newUsers', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->mediumText('AuthCode');
            $table->mediumText('UserJSON');
            $table->dateTime('Time')->useCurrent();
            $table->mediumText('Type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('newUsers');
    }
}
