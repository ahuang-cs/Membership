<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMeetsWithResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meetsWithResults', function (Blueprint $table) {
            $table->foreign('Gala', 'meetsWithResults_ibfk_1')->references('GalaID')->on('galas')->onUpdate('RESTRICT')->onDelete('SET NULL');
            $table->foreign('Tenant', 'mwr_tenant')->references('ID')->on('tenants')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meetsWithResults', function (Blueprint $table) {
            $table->dropForeign('meetsWithResults_ibfk_1');
            $table->dropForeign('mwr_tenant');
        });
    }
}
