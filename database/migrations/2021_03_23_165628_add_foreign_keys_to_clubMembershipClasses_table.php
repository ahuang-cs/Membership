<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToClubMembershipClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clubMembershipClasses', function (Blueprint $table) {
            $table->foreign('Tenant', 'clubMembershipClasses_tenant')->references('ID')->on('tenants')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clubMembershipClasses', function (Blueprint $table) {
            $table->dropForeign('clubMembershipClasses_tenant');
        });
    }
}
