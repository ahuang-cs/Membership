<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMemberEmailAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memberEmailAddresses', function (Blueprint $table) {
            $table->foreign('Member', 'memberEmailAddresses_ibfk_1')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memberEmailAddresses', function (Blueprint $table) {
            $table->dropForeign('memberEmailAddresses_ibfk_1');
        });
    }
}
