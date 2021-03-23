<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripePayments', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('User')->index('User');
            $table->dateTime('DateTime');
            $table->integer('Method')->nullable()->index('Method');
            $table->string('Intent')->nullable();
            $table->integer('Amount');
            $table->string('Currency', 3);
            $table->integer('ServedBy')->nullable()->index('ServedBy');
            $table->tinyInteger('Paid');
            $table->integer('AmountRefunded')->nullable()->default(0);
            $table->integer('Fees')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripePayments');
    }
}
