<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergencyContacts', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('UserID');
            $table->mediumText('Name');
            $table->mediumText('ContactNumber');
            $table->string('Relation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emergencyContacts');
    }
}
