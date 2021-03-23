<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStripeCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripeCustomers', function (Blueprint $table) {
            $table->foreign('User', 'stripeCustomers_ibfk_1')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripeCustomers', function (Blueprint $table) {
            $table->dropForeign('stripeCustomers_ibfk_1');
        });
    }
}
