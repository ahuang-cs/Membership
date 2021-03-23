<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTenantPaymentInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenantPaymentInvoiceItems', function (Blueprint $table) {
            $table->foreign('Invoice', 'tenantPaymentInvoiceItems_ibfk_1')->references('ID')->on('tenantPaymentInvoices')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenantPaymentInvoiceItems', function (Blueprint $table) {
            $table->dropForeign('tenantPaymentInvoiceItems_ibfk_1');
        });
    }
}
