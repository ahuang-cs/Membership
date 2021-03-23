<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsPendingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentsPending', function (Blueprint $table) {
            $table->integer('PaymentID', true);
            $table->date('Date');
            $table->enum('Status', ['Pending', 'Queued', 'Requested', 'Paid', 'Failed']);
            $table->integer('UserID');
            $table->string('Name', 500)->nullable();
            $table->integer('Amount');
            $table->string('Currency', 3);
            $table->string('PMkey', 20)->nullable();
            $table->enum('Type', ['Payment', 'Refund']);
            $table->mediumText('MetadataJSON')->nullable();
            $table->integer('Category')->nullable()->index('pcat2');
            $table->integer('Payment')->nullable()->index('paymentTotalRef');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paymentsPending');
    }
}
