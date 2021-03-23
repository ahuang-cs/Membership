<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantPaymentMandatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantPaymentMandates', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()');
            $table->string('MandateID', 256)->primary();
            $table->longText('AcceptanceData');
            $table->string('PaymentMethod', 256)->index('PaymentMethod');
            $table->longText('MethodDetails');
            $table->string('Status', 256);
            $table->string('UsageType', 256);
            $table->longText('UsageData')->nullable();
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
        Schema::dropIfExists('tenantPaymentMandates');
    }
}
