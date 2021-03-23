<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberEmailAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberEmailAddresses', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('Member')->index('Member');
            $table->string('EmailAddress', 320);
            $table->tinyInteger('Verified')->nullable()->default(0);
            $table->tinyInteger('Primary')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memberEmailAddresses');
    }
}
