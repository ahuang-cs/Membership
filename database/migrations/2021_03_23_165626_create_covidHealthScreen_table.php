<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidHealthScreenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covidHealthScreen', function (Blueprint $table) {
            $table->char('ID', 36)->primary();
            $table->integer('Member')->index('Member');
            $table->dateTime('DateTime')->useCurrent();
            $table->tinyInteger('OfficerApproval')->default(0);
            $table->integer('ApprovedBy')->nullable()->index('ApprovedBy');
            $table->text('Document')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('covidHealthScreen');
    }
}
