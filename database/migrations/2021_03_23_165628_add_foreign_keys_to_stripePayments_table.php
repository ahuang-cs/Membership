<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToStripePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripePayments', function (Blueprint $table) {
            $table->foreign('User', 'stripePayments_ibfk_1')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('Method', 'stripePayments_ibfk_2')->references('ID')->on('stripePayMethods')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('ServedBy', 'stripePayments_ibfk_3')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripePayments', function (Blueprint $table) {
            $table->dropForeign('stripePayments_ibfk_1');
            $table->dropForeign('stripePayments_ibfk_2');
            $table->dropForeign('stripePayments_ibfk_3');
        });
    }
}
