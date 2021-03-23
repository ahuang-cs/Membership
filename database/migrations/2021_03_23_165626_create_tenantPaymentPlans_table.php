<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantPaymentPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantPaymentPlans', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()')->primary();
            $table->char('Product', 36)->index('Product');
            $table->integer('PricePerUnit');
            $table->string('UsageType', 256);
            $table->char('Currency', 3);
            $table->string('BillingInterval', 256);
            $table->string('Name', 256);
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
        Schema::dropIfExists('tenantPaymentPlans');
    }
}
