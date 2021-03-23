<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStripePaymentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripePaymentItems', function (Blueprint $table) {
            $table->foreign('Category', 'pcat')->references('ID')->on('paymentCategories')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('Payment', 'stripePaymentItems_ibfk_1')->references('ID')->on('stripePayments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripePaymentItems', function (Blueprint $table) {
            $table->dropForeign('pcat');
            $table->dropForeign('stripePaymentItems_ibfk_1');
        });
    }
}
