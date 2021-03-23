<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRenewalMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('renewalMembers', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('PaymentID')->nullable();
            $table->integer('MemberID');
            $table->integer('RenewalID');
            $table->timestamp('Date')->useCurrent();
            $table->tinyInteger('CountRenewal')->default(0);
            $table->tinyInteger('Renewed')->nullable()->default(0);
            $table->enum('PaymentType', ['dd', 'cash', 'card', 'cheque', 'bacs', 'none'])->nullable()->default('dd');
            $table->integer('StripePayment')->nullable()->index('StripePayment');
            $table->tinyInteger('CashPaid')->nullable()->default(0);
            $table->tinyInteger('ChequePaid')->nullable()->default(0);
            $table->tinyInteger('BACSPaid')->nullable()->default(0);
            $table->string('BACSReference', 18)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('renewalMembers');
    }
}
