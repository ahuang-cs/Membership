<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->integer('MemberID', true);
            $table->integer('UserID')->nullable()->index('UserID');
            $table->tinyInteger('Status')->default(1);
            $table->tinyInteger('RR')->default(0);
            $table->string('AccessKey', 20);
            $table->string('MForename')->nullable();
            $table->string('MSurname')->nullable();
            $table->string('MMiddleNames')->nullable();
            $table->string('ASANumber')->nullable();
            $table->integer('ASACategory');
            $table->date('DateOfBirth');
            $table->enum('Gender', ['Male', 'Female']);
            $table->text('OtherNotes');
            $table->tinyInteger('RRTransfer')->nullable()->default(0);
            $table->tinyInteger('ASAPrimary')->default(1);
            $table->tinyInteger('ASAPaid')->default(0);
            $table->tinyInteger('ClubMember')->default(1);
            $table->tinyInteger('ClubPaid')->default(0);
            $table->tinyInteger('ASAMember')->default(1);
            $table->string('Country', 7)->default('GB-ENG');
            $table->string('PWHash', 200)->nullable();
            $table->integer('PWWrong')->nullable()->default(0);
            $table->tinyInteger('Active')->nullable()->default(1);
            $table->integer('Tenant')->default(1)->index('members_tenant');
            $table->char('ClubCategory', 36);
            $table->string('GenderIdentity', 256)->nullable();
            $table->string('GenderPronouns', 256)->nullable();
            $table->tinyInteger('GenderDisplay')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
