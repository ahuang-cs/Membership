<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMeetResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meetResults', function (Blueprint $table) {
            $table->foreign('Member', 'FK_MemberID')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('Meet', 'meetResults_ibfk_1')->references('Meet')->on('meetsWithResults')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meetResults', function (Blueprint $table) {
            $table->dropForeign('FK_MemberID');
            $table->dropForeign('meetResults_ibfk_1');
        });
    }
}
