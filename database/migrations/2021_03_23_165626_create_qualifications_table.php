<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qualifications', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()')->primary();
            $table->string('Name', 256);
            $table->text('Description')->nullable();
            $table->longText('DefaultExpiry');
            $table->tinyInteger('Show')->default(1);
            $table->integer('Tenant')->index('Tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qualifications');
    }
}
