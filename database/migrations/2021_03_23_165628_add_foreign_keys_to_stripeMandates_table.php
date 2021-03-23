<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStripeMandatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripeMandates', function (Blueprint $table) {
            $table->foreign('Customer', 'stripeMandates_ibfk_1')->references('CustomerID')->on('stripeCustomers')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripeMandates', function (Blueprint $table) {
            $table->dropForeign('stripeMandates_ibfk_1');
        });
    }
}
