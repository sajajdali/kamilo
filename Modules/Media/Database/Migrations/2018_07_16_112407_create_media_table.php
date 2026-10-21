<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->integer( 'id' )->nullable( FALSE )->autoIncrement()->unsigned();
            $table->integer( 'user_id' )->nullable( FALSE );
            $table->char( 'name', 200 )->nullable( FALSE );
            $table->text( 'path' )->nullable( FALSE );
            $table->char( 'type', 50 )->nullable( FALSE );
            $table->timestamps();
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
        Schema::dropIfExists('media');
    }
}
