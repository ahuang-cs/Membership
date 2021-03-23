<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSquadMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squadMembers', function (Blueprint $table) {
            $table->integer('Member');
            $table->integer('Squad')->index('Squad');
            $table->tinyInteger('Paying')->default(1);
            $table->primary(['Member', 'Squad']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('squadMembers');
    }
}
