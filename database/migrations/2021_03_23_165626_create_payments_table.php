<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->integer('PaymentID', true);
            $table->date('Date');
            $table->enum('Status', ['pending_api_request', 'pending_customer_approval', 'pending_submission', 'submitted', 'confirmed', 'paid_out', 'cancelled', 'customer_approval_denied', 'failed', 'charged_back', 'cust_not_dd', 'paid_manually', 'requires_payment_method', 'requires_confirmation', 'requires_action', 'processing', 'succeeded', 'canceled']);
            $table->integer('UserID');
            $table->integer('MandateID')->nullable();
            $table->string('Name', 50)->nullable();
            $table->integer('Amount');
            $table->string('Currency', 3);
            $table->string('PMkey', 20)->nullable();
            $table->enum('Type', ['Payment', 'Refund']);
            $table->string('Payout', 30)->nullable()->index('payments');
            $table->string('stripeMandate')->nullable();
            $table->string('stripePaymentIntent')->nullable();
            $table->string('stripePayout')->nullable();
            $table->string('stripeFailureCode', 256)->nullable();
            $table->integer('stripeFee')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
