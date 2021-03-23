<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetedListMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targetedListMembers', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('ListID')->index('ListID');
            $table->integer('ReferenceID');
            $table->enum('ReferenceType', ['User', 'Member']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('targetedListMembers');
    }
}
