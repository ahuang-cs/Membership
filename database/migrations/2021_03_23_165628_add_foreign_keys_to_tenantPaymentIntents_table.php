<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTenantPaymentIntentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenantPaymentIntents', function (Blueprint $table) {
            $table->foreign('PaymentMethod', 'tenantPaymentIntents_ibfk_1')->references('MethodID')->on('tenantPaymentMethods')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenantPaymentIntents', function (Blueprint $table) {
            $table->dropForeign('tenantPaymentIntents_ibfk_1');
        });
    }
}
