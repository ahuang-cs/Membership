<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->integer('Author')->nullable()->index('Author');
            $table->timestamp('Date')->useCurrent();
            $table->mediumText('Content');
            $table->mediumText('Title');
            $table->mediumText('Excerpt');
            $table->mediumText('Path');
            $table->timestamp('Modified')->useCurrent();
            $table->mediumText('Type');
            $table->mediumText('MIME');
            $table->integer('Tenant')->default(1)->index('p_oststenant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
