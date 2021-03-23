<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifyAdditionalEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifyAdditionalEmails', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('UserID')->index('UserID');
            $table->string('EmailAddress', 100);
            $table->string('Name', 50);
            $table->string('Hash')->nullable();
            $table->tinyInteger('Verified')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifyAdditionalEmails');
    }
}
