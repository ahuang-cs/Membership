<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCovidHealthScreenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('covidHealthScreen', function (Blueprint $table) {
            $table->foreign('Member', 'covidHealthScreen_ibfk_1')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('ApprovedBy', 'covidHealthScreen_ibfk_2')->references('UserID')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('covidHealthScreen', function (Blueprint $table) {
            $table->dropForeign('covidHealthScreen_ibfk_1');
            $table->dropForeign('covidHealthScreen_ibfk_2');
        });
    }
}
