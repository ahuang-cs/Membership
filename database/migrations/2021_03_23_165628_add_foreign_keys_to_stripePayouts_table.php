<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStripePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripePayouts', function (Blueprint $table) {
            $table->foreign('Tenant', 'stripePayouts_tenant')->references('ID')->on('tenants')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripePayouts', function (Blueprint $table) {
            $table->dropForeign('stripePayouts_tenant');
        });
    }
}
