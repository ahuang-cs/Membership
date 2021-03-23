<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCompletedFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('completedForms', function (Blueprint $table) {
            $table->foreign('User', 'completedForms_ibfk_1')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('Member', 'completedForms_ibfk_2')->references('MemberID')->on('members')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('completedForms', function (Blueprint $table) {
            $table->dropForeign('completedForms_ibfk_1');
            $table->dropForeign('completedForms_ibfk_2');
        });
    }
}
