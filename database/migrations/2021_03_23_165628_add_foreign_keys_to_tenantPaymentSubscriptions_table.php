<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTenantPaymentSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenantPaymentSubscriptions', function (Blueprint $table) {
            $table->foreign('Customer', 'tenantPaymentSubscriptions_ibfk_1')->references('CustomerID')->on('tenantStripeCustomers')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('PaymentMethod', 'tenantPaymentSubscriptions_ibfk_2')->references('MethodID')->on('tenantPaymentMethods')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenantPaymentSubscriptions', function (Blueprint $table) {
            $table->dropForeign('tenantPaymentSubscriptions_ibfk_1');
            $table->dropForeign('tenantPaymentSubscriptions_ibfk_2');
        });
    }
}
