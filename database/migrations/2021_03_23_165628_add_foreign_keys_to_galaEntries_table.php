<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGalaEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galaEntries', function (Blueprint $table) {
            $table->foreign('PaymentID', 'FK_PaymentID')->references('PaymentID')->on('paymentsPending')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('GalaID', 'galaEntries_ibfk_1')->references('GalaID')->on('galas')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('GalaID', 'galaEntries_ibfk_2')->references('GalaID')->on('galas')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('MemberID', 'galaEntries_ibfk_3')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('StripePayment', 'pay')->references('ID')->on('stripePayments')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('galaEntries', function (Blueprint $table) {
            $table->dropForeign('FK_PaymentID');
            $table->dropForeign('galaEntries_ibfk_1');
            $table->dropForeign('galaEntries_ibfk_2');
            $table->dropForeign('galaEntries_ibfk_3');
            $table->dropForeign('pay');
        });
    }
}
