<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRenewalMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('renewalMembers', function (Blueprint $table) {
            $table->foreign('StripePayment', 'renewalMembers_ibfk_1')->references('ID')->on('stripePayments')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('renewalMembers', function (Blueprint $table) {
            $table->dropForeign('renewalMembers_ibfk_1');
        });
    }
}
