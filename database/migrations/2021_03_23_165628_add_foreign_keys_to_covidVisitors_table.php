<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCovidVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('covidVisitors', function (Blueprint $table) {
            $table->foreign('Location', 'covidVisitors_ibfk_1')->references('ID')->on('covidLocations')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('Inputter', 'covidVisitors_ibfk_2')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('SET NULL');
            $table->foreign('Tenant', 'covidVisitors_ibfk_3')->references('ID')->on('tenants')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('covidVisitors', function (Blueprint $table) {
            $table->dropForeign('covidVisitors_ibfk_1');
            $table->dropForeign('covidVisitors_ibfk_2');
            $table->dropForeign('covidVisitors_ibfk_3');
        });
    }
}
