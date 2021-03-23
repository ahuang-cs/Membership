<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTrainingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainingLogs', function (Blueprint $table) {
            $table->foreign('Member', 'trainingLogs_ibfk_1')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trainingLogs', function (Blueprint $table) {
            $table->dropForeign('trainingLogs_ibfk_1');
        });
    }
}
