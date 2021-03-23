<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToJoinSwimmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('joinSwimmers', function (Blueprint $table) {
            $table->foreign('Parent', 'joinSwimmers_ibfk_1')->references('Hash')->on('joinParents')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('SquadSuggestion', 'joinSwimmers_ibfk_2')->references('SquadID')->on('squads')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('Tenant', 'js_tenant')->references('ID')->on('tenants')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('joinSwimmers', function (Blueprint $table) {
            $table->dropForeign('joinSwimmers_ibfk_1');
            $table->dropForeign('joinSwimmers_ibfk_2');
            $table->dropForeign('js_tenant');
        });
    }
}
