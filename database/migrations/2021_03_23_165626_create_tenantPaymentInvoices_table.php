<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantPaymentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantPaymentInvoices', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()')->primary();
            $table->char('Reference', 36)->default('uuid()');
            $table->string('Customer', 256)->index('Customer');
            $table->string('PaymentIntent', 256)->nullable()->index('PaymentIntent');
            $table->date('Date')->nullable()->useCurrent();
            $table->date('SupplyDate')->nullable()->useCurrent();
            $table->longText('Company')->nullable();
            $table->char('Currency', 3);
            $table->text('PaymentTerms')->nullable();
            $table->text('HowToPay')->nullable();
            $table->string('PurchaseOrderNumber', 256)->nullable();
            $table->integer('AmountPaidCash')->nullable();
            $table->date('PaidDate')->nullable();
            $table->tinyInteger('Paid')->default(0);
            $table->dateTime('Created')->useCurrent();
            $table->dateTime('Updated')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenantPaymentInvoices');
    }
}
