<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPaymentRetriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paymentRetries', function (Blueprint $table) {
            $table->foreign('UserID', 'paymentRetries_ibfk_1')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paymentRetries', function (Blueprint $table) {
            $table->dropForeign('paymentRetries_ibfk_1');
        });
    }
}
