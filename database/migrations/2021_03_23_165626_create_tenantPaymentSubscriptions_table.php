<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantPaymentSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantPaymentSubscriptions', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()')->primary();
            $table->string('Customer', 256)->index('Customer');
            $table->string('PaymentMethod', 256)->index('PaymentMethod');
            $table->text('Memo')->nullable();
            $table->text('Footer')->nullable();
            $table->date('StartDate');
            $table->date('EndDate')->nullable();
            $table->tinyInteger('Active')->default(1);
            $table->dateTime('Created')->useCurrent();
            $table->dateTime('Updated')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenantPaymentSubscriptions');
    }
}
