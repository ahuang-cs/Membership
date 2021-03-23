<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripePaymentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripePaymentItems', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('Payment')->index('Payment');
            $table->string('Name')->nullable();
            $table->text('Description')->nullable();
            $table->integer('Amount');
            $table->string('Currency', 3);
            $table->integer('AmountRefunded')->nullable()->default(0);
            $table->integer('Category')->nullable()->index('pcat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripePaymentItems');
    }
}
