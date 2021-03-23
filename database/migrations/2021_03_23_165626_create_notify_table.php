<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notify', function (Blueprint $table) {
            $table->integer('EmailID', true);
            $table->integer('MessageID')->nullable()->index('MessageID');
            $table->integer('UserID')->index('UserID');
            $table->enum('Status', ['Queued', 'Sent', 'No_Sub', 'Failed']);
            $table->mediumText('Subject')->nullable();
            $table->mediumText('Message')->nullable();
            $table->integer('Sender')->nullable()->index('Sender');
            $table->tinyInteger('ForceSend')->default(0);
            $table->mediumText('EmailType')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notify');
    }
}
