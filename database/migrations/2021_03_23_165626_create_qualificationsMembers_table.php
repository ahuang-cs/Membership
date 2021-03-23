<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualificationsMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qualificationsMembers', function (Blueprint $table) {
            $table->char('ID', 36)->default('uuid()')->primary();
            $table->char('Qualification', 36);
            $table->integer('Member')->index('Member');
            $table->date('ValidFrom')->useCurrent();
            $table->date('ValidUntil')->nullable();
            $table->text('Notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qualificationsMembers');
    }
}
