<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifyHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifyHistory', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('Sender');
            $table->mediumText('Subject');
            $table->mediumText('Message');
            $table->tinyInteger('ForceSend');
            $table->dateTime('Date');
            $table->mediumText('JSONData');
            $table->integer('Tenant')->default(1)->index('notify_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifyHistory');
    }
}
