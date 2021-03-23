<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMemberMedicalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memberMedical', function (Blueprint $table) {
            $table->foreign('MemberID', 'memberMedical_ibfk_1')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memberMedical', function (Blueprint $table) {
            $table->dropForeign('memberMedical_ibfk_1');
        });
    }
}
