<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripePayMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripePayMethods', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->string('Customer');
            $table->string('MethodID');
            $table->string('CardName')->nullable();
            $table->string('City')->nullable();
            $table->string('Country')->nullable();
            $table->string('Line1')->nullable();
            $table->string('Line2')->nullable();
            $table->string('PostCode')->nullable();
            $table->string('Brand')->nullable();
            $table->string('IssueCountry')->nullable();
            $table->integer('ExpMonth')->nullable();
            $table->integer('ExpYear')->nullable();
            $table->string('Funding')->nullable();
            $table->string('Last4')->nullable();
            $table->string('Name')->nullable();
            $table->string('Fingerprint')->nullable();
            $table->tinyInteger('Reusable')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripePayMethods');
    }
}
