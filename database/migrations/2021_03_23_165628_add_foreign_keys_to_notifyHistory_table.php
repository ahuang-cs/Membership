<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToNotifyHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifyHistory', function (Blueprint $table) {
            $table->foreign('Tenant', 'notify_tenant')->references('ID')->on('tenants')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifyHistory', function (Blueprint $table) {
            $table->dropForeign('notify_tenant');
        });
    }
}
