<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantPaymentInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantPaymentInvoiceItems', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()')->primary();
            $table->char('Invoice', 36)->default('uuid()')->index('Invoice');
            $table->longText('Description');
            $table->integer('Amount');
            $table->char('Currency', 3)->nullable();
            $table->enum('Type', ['credit', 'debit'])->nullable();
            $table->integer('Quantity')->nullable()->default(1);
            $table->integer('PricePerUnit');
            $table->integer('VATAmount')->nullable();
            $table->integer('VATRate')->nullable();
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
        Schema::dropIfExists('tenantPaymentInvoiceItems');
    }
}
