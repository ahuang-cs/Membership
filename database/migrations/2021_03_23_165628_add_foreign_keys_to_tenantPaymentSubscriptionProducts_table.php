<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTenantPaymentSubscriptionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenantPaymentSubscriptionProducts', function (Blueprint $table) {
            $table->foreign('Subscription', 'sub_fk')->references('ID')->on('tenantPaymentSubscriptions')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('Plan', 'tenantPaymentSubscriptionProducts_ibfk_1')->references('ID')->on('tenantPaymentPlans')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenantPaymentSubscriptionProducts', function (Blueprint $table) {
            $table->dropForeign('sub_fk');
            $table->dropForeign('tenantPaymentSubscriptionProducts_ibfk_1');
        });
    }
}
