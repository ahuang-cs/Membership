<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('walletHistory', function (Blueprint $table) {
            $table->integer('WalletHistoryID', true);
            $table->double('Amount');
            $table->double('Balance');
            $table->integer('UserID');
            $table->string('TransactionDesc', 60);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('walletHistory');
    }
}
