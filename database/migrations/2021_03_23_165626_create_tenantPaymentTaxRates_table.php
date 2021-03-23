<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantPaymentTaxRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantPaymentTaxRates', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()')->primary();
            $table->string('Name', 256);
            $table->string('Type', 256);
            $table->string('Region', 256);
            $table->integer('Rate');
            $table->string('InclusiveExclusive', 256);
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
        Schema::dropIfExists('tenantPaymentTaxRates');
    }
}
