<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentMonths', function (Blueprint $table) {
            $table->integer('MonthID', true);
            $table->string('MonthStart', 7);
            $table->date('Date');
            $table->integer('Tenant')->default(1)->index('pmonths_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paymentMonths');
    }
}
