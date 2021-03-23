<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJoinSwimmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joinSwimmers', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->char('Parent', 40)->index('Parent');
            $table->string('First', 30);
            $table->string('Last', 40);
            $table->date('DoB');
            $table->integer('XP');
            $table->text('XPDetails')->nullable();
            $table->text('Medical')->nullable();
            $table->text('Questions')->nullable();
            $table->string('Club', 30)->nullable();
            $table->string('ASA')->nullable();
            $table->dateTime('TrialStart')->nullable();
            $table->dateTime('TrialEnd')->nullable();
            $table->text('Comments')->nullable();
            $table->integer('SquadSuggestion')->nullable()->index('SquadSuggestion');
            $table->enum('Sex', ['Male', 'Female', 'Other']);
            $table->integer('Tenant')->default(1)->index('js_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('joinSwimmers');
    }
}
