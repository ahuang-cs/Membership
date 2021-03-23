<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSuperUsersLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('superUsersLogins', function (Blueprint $table) {
            $table->foreign('User', 'superUsersLogins_ibfk_1')->references('ID')->on('superUsers')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('superUsersLogins', function (Blueprint $table) {
            $table->dropForeign('superUsersLogins_ibfk_1');
        });
    }
}
