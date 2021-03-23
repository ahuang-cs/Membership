<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeDisputesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripeDisputes', function (Blueprint $table) {
            $table->string('ID')->primary();
            $table->string('SID');
            $table->integer('Amount')->default(0);
            $table->string('Currency', 3);
            $table->string('PaymentIntent')->nullable();
            $table->string('Reason');
            $table->string('Status');
            $table->dateTime('Created')->nullable();
            $table->dateTime('EvidenceDueBy')->nullable();
            $table->tinyInteger('IsRefundable')->default(0);
            $table->tinyInteger('HasEvidence')->default(0);
            $table->tinyInteger('EvidencePastDue')->default(0);
            $table->integer('EvidenceSubmissionCount')->default(0);
            $table->integer('Tenant')->default(1)->index('sd_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripeDisputes');
    }
}
