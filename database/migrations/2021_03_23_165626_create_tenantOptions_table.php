<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantOptions', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->string('Option')->nullable();
            $table->mediumText('Value');
            $table->integer('Tenant')->default(1)->index('options_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenantOptions');
    }
}
