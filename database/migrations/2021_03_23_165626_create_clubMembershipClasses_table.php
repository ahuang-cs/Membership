<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClubMembershipClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clubMembershipClasses', function (Blueprint $table) {
            $table->char('ID', 36)->primary();
            $table->string('Name', 256);
            $table->text('Description')->nullable();
            $table->longText('Fees');
            $table->integer('Tenant')->default(1)->index('clubMembershipClasses_tenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clubMembershipClasses');
    }
}
