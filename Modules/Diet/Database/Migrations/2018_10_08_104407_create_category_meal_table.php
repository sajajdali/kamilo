<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryMealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_meal', function (Blueprint $table) {
            $table->integer('meal_id')->unsigned()->index();
            $table->integer('meal_category_id')->unsigned()->index();

            $table->foreign('meal_id')->references('id')->on('meal')->onDelete('cascade');
            $table->foreign('meal_category_id')->references('id')->on('meal_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_meal');
    }
}
