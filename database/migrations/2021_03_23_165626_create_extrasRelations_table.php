<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtrasRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extrasRelations', function (Blueprint $table) {
            $table->integer('RelationID', true);
            $table->integer('ExtraID')->index('ExtraID');
            $table->integer('MemberID')->nullable()->index('MemberID');
            $table->integer('UserID')->nullable()->index('UserID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extrasRelations');
    }
}
