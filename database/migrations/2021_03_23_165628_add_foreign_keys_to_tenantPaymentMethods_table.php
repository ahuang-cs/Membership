<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTenantPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenantPaymentMethods', function (Blueprint $table) {
            $table->foreign('Customer', 'tenantPaymentMethods_ibfk_1')->references('CustomerID')->on('tenantStripeCustomers')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenantPaymentMethods', function (Blueprint $table) {
            $table->dropForeign('tenantPaymentMethods_ibfk_1');
        });
    }
}
