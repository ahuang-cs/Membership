<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripePayouts', function (Blueprint $table) {
            $table->string('ID')->primary();
            $table->integer('Amount');
            $table->date('ArrivalDate')->nullable();
            $table->integer('Tenant')->default(1)->index('stripePayouts_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripePayouts');
    }
}
