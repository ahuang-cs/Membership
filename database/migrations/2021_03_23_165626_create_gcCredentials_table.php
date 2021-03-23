<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGcCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gcCredentials', function (Blueprint $table) {
            $table->string('OrganisationId', 256);
            $table->string('AccessToken', 256);
            $table->integer('Tenant')->index('Tenant');
            $table->primary(['OrganisationId', 'Tenant']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gcCredentials');
    }
}
