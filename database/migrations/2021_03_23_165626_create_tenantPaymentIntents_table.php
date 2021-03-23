<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantPaymentIntentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantPaymentIntents', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()');
            $table->string('IntentID', 256)->primary();
            $table->string('PaymentMethod', 256)->nullable()->index('PaymentMethod');
            $table->string('Review', 256)->nullable();
            $table->integer('Amount');
            $table->char('Currency', 3);
            $table->string('Status', 256)->nullable();
            $table->longText('Shipping')->nullable();
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
        Schema::dropIfExists('tenantPaymentIntents');
    }
}
