<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->integer( 'id' )->nullable( FALSE )->autoIncrement()->unsigned()->unique();
            $table->smallInteger( 'menu_id' )->nullable( FALSE )->unsigned()->index();
            $table->integer( 'parent_id' )->nullable( FALSE )->unsigned();
            $table->text( 'title' )->nullable( FALSE );
            $table->text( 'link' )->nullable( FALSE );
            $table->text( 'content' )->nullable( FALSE );
            $table->integer( 'priority' )->nullable( FALSE );
            $table->foreign('menu_id')
                ->references('id')->on('menu')
                ->onDelete('cascade')
                ->onUpdate('no action');
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
        Schema::dropIfExists('menu_items');
    }
}
