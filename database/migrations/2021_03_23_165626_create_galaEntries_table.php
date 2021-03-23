<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalaEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galaEntries', function (Blueprint $table) {
            $table->integer('EntryID', true);
            $table->integer('GalaID')->index('GalaID');
            $table->integer('MemberID')->index('MemberID');
            $table->tinyInteger('EntryProcessed');
            $table->tinyInteger('TimesRequired')->nullable()->comment('If true, times required from coaches');
            $table->tinyInteger('TimesProvided')->nullable()->comment('Set true if times provided for a non HyTek Gala');
            $table->double('FeeToPay')->nullable();
            $table->tinyInteger('Charged')->default(0);
            $table->tinyInteger('50Free')->nullable();
            $table->tinyInteger('100Free')->nullable();
            $table->tinyInteger('200Free')->nullable();
            $table->tinyInteger('400Free')->nullable();
            $table->tinyInteger('800Free')->nullable();
            $table->tinyInteger('1500Free')->nullable();
            $table->tinyInteger('50Breast')->nullable();
            $table->tinyInteger('100Breast')->nullable();
            $table->tinyInteger('200Breast')->nullable();
            $table->tinyInteger('50Fly')->nullable();
            $table->tinyInteger('100Fly')->nullable();
            $table->tinyInteger('200Fly')->nullable();
            $table->tinyInteger('50Back')->nullable();
            $table->tinyInteger('100Back')->nullable();
            $table->tinyInteger('200Back')->nullable();
            $table->tinyInteger('200IM')->nullable();
            $table->tinyInteger('400IM')->nullable();
            $table->tinyInteger('100IM')->nullable();
            $table->tinyInteger('150IM')->nullable();
            $table->text('50FreeTime')->nullable();
            $table->text('100FreeTime')->nullable();
            $table->text('200FreeTime')->nullable();
            $table->text('400FreeTime')->nullable();
            $table->text('800FreeTime')->nullable();
            $table->text('1500FreeTime')->nullable();
            $table->text('50BreastTime')->nullable();
            $table->text('100BreastTime')->nullable();
            $table->text('200BreastTime')->nullable();
            $table->text('50FlyTime')->nullable();
            $table->text('100FlyTime')->nullable();
            $table->text('200FlyTime')->nullable();
            $table->text('50BackTime')->nullable();
            $table->text('100BackTime')->nullable();
            $table->text('200BackTime')->nullable();
            $table->text('200IMTime')->nullable();
            $table->text('400IMTime')->nullable();
            $table->text('100IMTime')->nullable();
            $table->text('150IMTime')->nullable();
            $table->tinyInteger('Refunded')->nullable()->default(0);
            $table->tinyInteger('Locked')->nullable()->default(0);
            $table->tinyInteger('Vetoable')->nullable()->default(0);
            $table->integer('AmountRefunded')->nullable()->default(0);
            $table->integer('StripePayment')->nullable()->index('pay');
            $table->tinyInteger('Approved')->nullable()->default(1);
            $table->integer('PaymentID')->nullable()->index('FK_PaymentID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('galaEntries');
    }
}
