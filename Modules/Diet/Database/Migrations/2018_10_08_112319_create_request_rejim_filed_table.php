<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestRejimFiledTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_rejim_filed', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_rejim_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->integer('rejim_type')->default('0');
            $table->integer('meal');
            $table->integer('meal_id')->default('0');
            $table->tinyInteger('day');
            $table->float('calorie');
            $table->tinyInteger('repeat')->default('0');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('request_rejim_id')->references('id')->on('request_rejim_program')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_rejim_filed');
    }
}
