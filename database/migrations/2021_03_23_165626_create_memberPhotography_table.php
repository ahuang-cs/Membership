<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberPhotographyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberPhotography', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('MemberID');
            $table->tinyInteger('Website');
            $table->tinyInteger('Social');
            $table->tinyInteger('Noticeboard');
            $table->tinyInteger('FilmTraining');
            $table->tinyInteger('ProPhoto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memberPhotography');
    }
}
