<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualFeeTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individualFeeTrack', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('MonthID');
            $table->integer('PaymentID')->nullable();
            $table->integer('MemberID');
            $table->integer('UserID')->nullable();
            $table->mediumText('Description');
            $table->integer('Amount');
            $table->enum('Type', ['SquadFee', 'ExtraFee']);
            $table->tinyInteger('NC')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('individualFeeTrack');
    }
}
