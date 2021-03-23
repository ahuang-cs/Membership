<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renewalData', function (Blueprint $table) {
            $table->char('ID', 36)->primary();
            $table->char('Renewal', 36)->nullable()->index('Renewal');
            $table->integer('User')->nullable()->index('User');
            $table->longText('Document');
            $table->integer('PaymentIntent')->nullable()->index('PaymentIntent');
            $table->integer('PaymentDD')->nullable()->index('PaymentDD');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('renewalData');
    }
}
