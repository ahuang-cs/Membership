<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExtrasRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extrasRelations', function (Blueprint $table) {
            $table->foreign('ExtraID', 'extrasRelations_ibfk_1')->references('ExtraID')->on('extras')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('MemberID', 'extrasRelations_ibfk_2')->references('MemberID')->on('members')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('UserID', 'extrasRelations_ibfk_3')->references('UserID')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extrasRelations', function (Blueprint $table) {
            $table->dropForeign('extrasRelations_ibfk_1');
            $table->dropForeign('extrasRelations_ibfk_2');
            $table->dropForeign('extrasRelations_ibfk_3');
        });
    }
}
