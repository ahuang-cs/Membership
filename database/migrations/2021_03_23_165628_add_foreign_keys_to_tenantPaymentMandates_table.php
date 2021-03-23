<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTenantPaymentMandatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenantPaymentMandates', function (Blueprint $table) {
            $table->foreign('PaymentMethod', 'tenantPaymentMandates_ibfk_1')->references('MethodID')->on('tenantPaymentMethods')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenantPaymentMandates', function (Blueprint $table) {
            $table->dropForeign('tenantPaymentMandates_ibfk_1');
        });
    }
}
