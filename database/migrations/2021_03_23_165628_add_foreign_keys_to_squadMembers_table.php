<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSquadMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('squadMembers', function (Blueprint $table) {
            $table->foreign('Member', 'squadMembers_ibfk_1')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('Squad', 'squadMembers_ibfk_2')->references('SquadID')->on('squads')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('squadMembers', function (Blueprint $table) {
            $table->dropForeign('squadMembers_ibfk_1');
            $table->dropForeign('squadMembers_ibfk_2');
        });
    }
}
