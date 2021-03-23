<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeMandatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripeMandates', function (Blueprint $table) {
            $table->string('ID')->primary();
            $table->string('Mandate');
            $table->string('Customer')->index('Customer');
            $table->string('Fingerprint');
            $table->string('Last4', 4);
            $table->string('SortCode', 8);
            $table->string('Address', 1024);
            $table->enum('Status', ['pending', 'revoked', 'refused', 'accepted']);
            $table->enum('MandateStatus', ['active', 'inactive', 'pending']);
            $table->dateTime('CreationTime')->useCurrent();
            $table->string('Reference', 64);
            $table->string('URL', 512);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripeMandates');
    }
}
