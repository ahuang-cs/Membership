<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPaymentMandatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paymentMandates', function (Blueprint $table) {
            $table->foreign('UserID', 'paymentMandates_ibfk_1')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paymentMandates', function (Blueprint $table) {
            $table->dropForeign('paymentMandates_ibfk_1');
        });
    }
}
