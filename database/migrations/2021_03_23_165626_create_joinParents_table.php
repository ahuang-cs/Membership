<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJoinParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joinParents', function (Blueprint $table) {
            $table->char('Hash', 40)->unique('Hash');
            $table->string('First', 30);
            $table->string('Last', 40);
            $table->string('Email', 70);
            $table->tinyInteger('Invited')->default(0);
            $table->integer('Tenant')->default(1)->index('jp_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('joinParents');
    }
}
