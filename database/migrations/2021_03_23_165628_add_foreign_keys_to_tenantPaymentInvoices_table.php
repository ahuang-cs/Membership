<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTenantPaymentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenantPaymentInvoices', function (Blueprint $table) {
            $table->foreign('Customer', 'tenantPaymentInvoices_ibfk_1')->references('CustomerID')->on('tenantStripeCustomers')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('PaymentIntent', 'tenantPaymentInvoices_ibfk_2')->references('IntentID')->on('tenantPaymentIntents')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenantPaymentInvoices', function (Blueprint $table) {
            $table->dropForeign('tenantPaymentInvoices_ibfk_1');
            $table->dropForeign('tenantPaymentInvoices_ibfk_2');
        });
    }
}
