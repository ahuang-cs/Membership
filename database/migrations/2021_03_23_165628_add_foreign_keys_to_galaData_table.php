<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGalaDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galaData', function (Blueprint $table) {
            $table->foreign('Gala', 'galaData_ibfk_1')->references('GalaID')->on('galas')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('galaData', function (Blueprint $table) {
            $table->dropForeign('galaData_ibfk_1');
        });
    }
}
