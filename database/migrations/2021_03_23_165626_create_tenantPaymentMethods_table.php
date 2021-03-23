<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantPaymentMethods', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()');
            $table->string('MethodID', 256)->primary();
            $table->string('Customer', 256)->index('Customer');
            $table->longText('BillingDetails')->nullable();
            $table->string('Type', 256);
            $table->longText('TypeData')->nullable();
            $table->string('Fingerprint', 256)->nullable();
            $table->tinyInteger('Usable')->nullable()->default(0);
            $table->dateTime('Created')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenantPaymentMethods');
    }
}
