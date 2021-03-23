<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetedListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targetedLists', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->mediumText('Name');
            $table->mediumText('Description');
            $table->integer('Tenant')->default(1)->index('tl_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('targetedLists');
    }
}
