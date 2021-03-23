<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthAuthorizationCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_authorization_codes', function (Blueprint $table) {
            $table->string('authorization_code', 40)->primary();
            $table->string('client_id', 80);
            $table->string('user_id', 80)->nullable();
            $table->string('redirect_uri', 2000)->nullable();
            $table->timestamp('expires')->useCurrent();
            $table->string('scope', 4000)->nullable();
            $table->string('id_token', 1000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_authorization_codes');
    }
}
