<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovidRiskAwarenessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covidRiskAwareness', function (Blueprint $table) {
            $table->char('ID', 36)->primary();
            $table->dateTime('DateTime')->useCurrent();
            $table->integer('Member')->index('Member');
            $table->tinyInteger('MemberAgreement')->default(0);
            $table->integer('Guardian')->nullable()->index('Guardian');
            $table->tinyInteger('GuardianAgreement')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('covidRiskAwareness');
    }
}
