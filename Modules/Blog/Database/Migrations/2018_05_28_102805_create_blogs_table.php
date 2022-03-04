<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->string('title');
            $table->text('home_text');
            $table->text('body_text');
            $table->integer('counter');
            $table->boolean('comment');
            $table->boolean('type');
            $table->boolean('published')->default('1');
            $table->boolean('show_at_home_top')->default('0');
            $table->boolean('show_at_home_bottom')->default('0');
            $table->tinyInteger('main_page')->default('1');
            $table->text('details');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
