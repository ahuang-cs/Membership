<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTargetedListMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('targetedListMembers', function (Blueprint $table) {
            $table->foreign('ListID', 'targetedListMembers_ibfk_1')->references('ID')->on('targetedLists')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('targetedListMembers', function (Blueprint $table) {
            $table->dropForeign('targetedListMembers_ibfk_1');
        });
    }
}
