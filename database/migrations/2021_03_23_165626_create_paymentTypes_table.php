<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentTypes', function (Blueprint $table) {
            $table->integer('PayTypeID', true);
            $table->string('PayTypeName', 60);
            $table->string('PayTypeDescription', 200)->nullable();
            $table->tinyInteger('PayTypeEnabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paymentTypes');
    }
}
