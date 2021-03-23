<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMandatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentMandates', function (Blueprint $table) {
            $table->integer('MandateID', true);
            $table->integer('UserID')->index('UserID');
            $table->string('Name', 20);
            $table->string('Mandate', 20);
            $table->string('Customer', 20);
            $table->string('BankAccount', 20);
            $table->string('BankName', 100)->nullable();
            $table->string('AccountHolderName', 30);
            $table->string('AccountNumEnd', 2);
            $table->tinyInteger('InUse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paymentMandates');
    }
}
