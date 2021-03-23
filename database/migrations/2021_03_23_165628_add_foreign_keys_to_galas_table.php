<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galas', function (Blueprint $table) {
            $table->foreign('Tenant', 'galas_tenant')->references('ID')->on('tenants')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('PaymentCategory', 'pcat3')->references('ID')->on('paymentCategories')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('galas', function (Blueprint $table) {
            $table->dropForeign('galas_tenant');
            $table->dropForeign('pcat3');
        });
    }
}
