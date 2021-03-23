<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extras', function (Blueprint $table) {
            $table->integer('ExtraID', true);
            $table->string('ExtraName', 100);
            $table->decimal('ExtraFee', 6);
            $table->enum('Type', ['Payment', 'Refund'])->nullable()->default('Payment');
            $table->integer('Tenant')->default(1)->index('extras_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extras');
    }
}
