<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPaymentsPendingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paymentsPending', function (Blueprint $table) {
            $table->foreign('Payment', 'paymentTotalRef')->references('PaymentID')->on('payments')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('Category', 'pcat2')->references('ID')->on('paymentCategories')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paymentsPending', function (Blueprint $table) {
            $table->dropForeign('paymentTotalRef');
            $table->dropForeign('pcat2');
        });
    }
}
