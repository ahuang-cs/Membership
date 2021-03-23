<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('Tenant', 'p_oststenant')->references('ID')->on('tenants')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('Author', 'posts_ibfk_3')->references('UserID')->on('users')->onUpdate('RESTRICT')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('p_oststenant');
            $table->dropForeign('posts_ibfk_3');
        });
    }
}
