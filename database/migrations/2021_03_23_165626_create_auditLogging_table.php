<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLoggingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditLogging', function (Blueprint $table) {
            $table->char('ID', 36)->primary();
            $table->integer('User')->index('User');
            $table->timestamp('Time')->useCurrent();
            $table->string('Event', 256);
            $table->string('Description', 512);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auditLogging');
    }
}
