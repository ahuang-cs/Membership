<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galas', function (Blueprint $table) {
            $table->integer('GalaID', true);
            $table->string('GalaName')->nullable();
            $table->enum('CourseLength', ['SHORT', 'LONG', 'IRREGULAR'])->nullable();
            $table->string('GalaVenue')->nullable();
            $table->decimal('GalaFee')->nullable();
            $table->tinyInteger('GalaFeeConstant')->default(1);
            $table->date('ClosingDate')->comment('The closing date of the gala');
            $table->date('GalaDate')->comment('Last day of gala when the event will disappear from accounts');
            $table->tinyInteger('HyTek');
            $table->tinyInteger('CoachEnters')->nullable()->default(0);
            $table->tinyInteger('RequiresApproval')->nullable()->default(0);
            $table->text('Description')->nullable();
            $table->integer('PaymentCategory')->nullable()->index('pcat3');
            $table->integer('Tenant')->default(1)->index('galas_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('galas');
    }
}
