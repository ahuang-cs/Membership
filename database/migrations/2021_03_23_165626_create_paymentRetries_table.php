<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRetriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentRetries', function (Blueprint $table) {
            $table->integer('UserID')->index('UserID');
            $table->date('Day');
            $table->string('PMKey', 256)->index('PMKey');
            $table->tinyInteger('Tried')->default(0);
            $table->primary(['UserID', 'Day']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paymentRetries');
    }
}
