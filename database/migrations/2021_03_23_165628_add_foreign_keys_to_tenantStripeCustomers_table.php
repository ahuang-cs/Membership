<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTenantStripeCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenantStripeCustomers', function (Blueprint $table) {
            $table->foreign('Tenant', 'tenantStripeCustomers_ibfk_1')->references('ID')->on('tenants')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenantStripeCustomers', function (Blueprint $table) {
            $table->dropForeign('tenantStripeCustomers_ibfk_1');
        });
    }
}
