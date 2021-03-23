<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTenantPaymentPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenantPaymentPlans', function (Blueprint $table) {
            $table->foreign('Product', 'tenantPaymentPlans_ibfk_1')->references('ID')->on('tenantPaymentProducts')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenantPaymentPlans', function (Blueprint $table) {
            $table->dropForeign('tenantPaymentPlans_ibfk_1');
        });
    }
}
