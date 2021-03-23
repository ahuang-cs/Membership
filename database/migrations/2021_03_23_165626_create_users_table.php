<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('UserID', true);
            $table->string('Username', 65)->nullable()->unique('Username');
            $table->integer('GID')->nullable()->comment('If G Suite User, store the ID here for automatic passwords');
            $table->string('Password');
            $table->string('EmailAddress');
            $table->tinyInteger('EmailComms');
            $table->mediumText('Forename');
            $table->mediumText('Surname');
            $table->text('Mobile');
            $table->tinyInteger('MobileComms');
            $table->tinyInteger('RR')->default(0);
            $table->dateTime('Edit')->useCurrent();
            $table->integer('WrongPassCount')->default(0);
            $table->tinyInteger('Active')->nullable()->default(1);
            $table->integer('Tenant')->default(1)->index('users_tenant');
            $table->unique(['Username', 'EmailAddress'], 'Username_2');
            $table->unique(['EmailAddress', 'Tenant'], 'UniqueTenantEmail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
