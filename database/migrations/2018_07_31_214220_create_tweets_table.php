<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('external_author_id')->nullable();
            $table->text('author');
            $table->text('content');
            $table->string('region', 50);
            $table->string('language', 25);
            $table->timestamp('publish_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('harvested_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('following');
            $table->integer('followers');
            $table->integer('updates');
            $table->string('post_type', 15);
            $table->string('account_type', 25);
            $table->boolean('retweet');
            $table->string('account_category', 25);
            $table->boolean('new_june_2018');
            $table->timestamps();

            // Which fields to index?
            $table->index('language');
            $table->index('region');
            $table->index('post_type');
            $table->index('account_type');
            $table->index('account_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweets');
    }
}
