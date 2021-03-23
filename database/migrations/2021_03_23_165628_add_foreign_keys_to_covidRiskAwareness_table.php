<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCovidRiskAwarenessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('covidRiskAwareness', function (Blueprint $table) {
            $table->foreign('Member', 'covidRiskAwareness_ibfk_1')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('Guardian', 'covidRiskAwareness_ibfk_2')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('covidRiskAwareness', function (Blueprint $table) {
            $table->dropForeign('covidRiskAwareness_ibfk_1');
            $table->dropForeign('covidRiskAwareness_ibfk_2');
        });
    }
}
