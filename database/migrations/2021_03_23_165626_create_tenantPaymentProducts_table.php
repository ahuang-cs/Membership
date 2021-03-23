<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantPaymentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenantPaymentProducts', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()')->primary();
            $table->string('Name', 256);
            $table->text('Description')->nullable();
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
        Schema::dropIfExists('tenantPaymentProducts');
    }
}
