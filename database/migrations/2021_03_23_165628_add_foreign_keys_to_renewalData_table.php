<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRenewalDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('renewalData', function (Blueprint $table) {
            $table->foreign('Renewal', 'renewalData_ibfk_1')->references('ID')->on('renewalPeriods')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('User', 'renewalData_ibfk_2')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('PaymentIntent', 'renewalData_ibfk_3')->references('ID')->on('stripePayments')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('PaymentDD', 'renewalData_ibfk_4')->references('PaymentID')->on('paymentsPending')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('renewalData', function (Blueprint $table) {
            $table->dropForeign('renewalData_ibfk_1');
            $table->dropForeign('renewalData_ibfk_2');
            $table->dropForeign('renewalData_ibfk_3');
            $table->dropForeign('renewalData_ibfk_4');
        });
    }
}
