<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentCategories', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->string('Name', 100);
            $table->string('Description', 200);
            $table->integer('Tenant')->default(1)->index('pc_tenant');
            $table->string('UniqueID', 36);
            $table->tinyInteger('Show')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paymentCategories');
    }
}
