<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passwordTokens', function (Blueprint $table) {
            $table->integer('TokenID', true);
            $table->integer('UserID');
            $table->mediumText('Token');
            $table->date('Date')->nullable();
            $table->enum('Type', ['Password_Reset', 'Account_Verification']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passwordTokens');
    }
}
