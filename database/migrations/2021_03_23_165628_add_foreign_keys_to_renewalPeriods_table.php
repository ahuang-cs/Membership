<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRenewalPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('renewalPeriods', function (Blueprint $table) {
            $table->foreign('Tenant', 'renewalPeriods_ibfk_1')->references('ID')->on('tenants')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('renewalPeriods', function (Blueprint $table) {
            $table->dropForeign('renewalPeriods_ibfk_1');
        });
    }
}
