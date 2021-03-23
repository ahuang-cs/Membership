<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberMedicalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberMedical', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('MemberID')->unique('MemberID_2');
            $table->mediumText('Conditions')->nullable();
            $table->mediumText('Allergies')->nullable();
            $table->mediumText('Medication')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memberMedical');
    }
}
