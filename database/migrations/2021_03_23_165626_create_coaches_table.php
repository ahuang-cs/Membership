<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coaches', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('User');
            $table->integer('Squad')->index('Squad');
            $table->enum('Type', ['LEAD_COACH', 'COACH', 'ASSISTANT_COACH', 'TEACHER', 'HELPER', 'ADMINISTRATOR']);
            $table->unique(['User', 'Squad'], 'unique_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coaches');
    }
}
