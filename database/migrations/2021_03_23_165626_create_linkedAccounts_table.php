<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinkedAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linkedAccounts', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('User')->index('User');
            $table->integer('LinkedUser')->index('LinkedUser');
            $table->string('Key')->nullable();
            $table->tinyInteger('Active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linkedAccounts');
    }
}
