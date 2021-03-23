<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantPaymentSubscriptionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantPaymentSubscriptionProducts', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()')->primary();
            $table->char('Subscription', 36)->default('uuid()')->index('sub_fk');
            $table->char('Plan', 36)->index('Plan');
            $table->integer('Quantity');
            $table->date('NextBills');
            $table->char('TaxRate', 36)->nullable();
            $table->integer('Discount')->nullable();
            $table->string('DiscountType', 256)->nullable();
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
        Schema::dropIfExists('tenantPaymentSubscriptionProducts');
    }
}
