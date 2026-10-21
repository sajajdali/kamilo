<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->smallInteger( 'id' )->nullable( FALSE )->autoIncrement()->unsigned()->unique();
            $table->char( 'title', 255 )->nullable( FALSE );
            $table->char( 'menu_key', 100 )->nullable( FALSE );
            $table->text( 'setting' )->nullable( FALSE );
            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
}
