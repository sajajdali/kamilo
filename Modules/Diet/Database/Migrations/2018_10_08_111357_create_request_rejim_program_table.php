<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestRejimProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_rejim_program', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tid')->nullable();
            $table->tinyInteger('request_type');
            $table->tinyInteger('rejim_type');
            $table->tinyInteger('rejim_body');
            $table->unsignedInteger('user_id')->nullable();
            $table->double('calorie');
            $table->double('number_request');
            $table->double('height');
            $table->double('weight');
            $table->double('target_weight');
            $table->double('wrist');
            $table->tinyInteger('active');
            $table->tinyInteger('inUse');
            $table->integer('no_shift');
            $table->tinyInteger('offline');
            $table->string('link');
            $table->timestamp('date_end');
            $table->char('rejim_level', 1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_rejim_program');
    }
}
