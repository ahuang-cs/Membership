<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompletedFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('completedForms', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('Member')->nullable()->index('Member');
            $table->integer('User')->nullable()->index('User');
            $table->string('Form')->nullable();
            $table->date('Date');
            $table->text('About')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('completedForms');
    }
}
