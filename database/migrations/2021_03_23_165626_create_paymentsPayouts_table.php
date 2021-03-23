<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsPayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentsPayouts', function (Blueprint $table) {
            $table->string('ID', 30)->primary();
            $table->integer('Amount');
            $table->integer('Fees');
            $table->char('Currency', 3);
            $table->date('ArrivalDate')->nullable();
            $table->integer('Tenant')->default(1)->index('pp_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paymentsPayouts');
    }
}
