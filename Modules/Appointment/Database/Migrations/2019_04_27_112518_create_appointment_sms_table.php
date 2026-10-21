<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_sms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('appointment_part_id');
            $table->unsignedInteger('user_id');
            $table->text('message');
            $table->boolean('day_before')->comment('چند روز قبل برای کاربر پیامک ارسال شود');
            $table->boolean('status');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');
            $table->foreign('appointment_part_id')
                ->references('id')
                ->on('appointment_parts')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_sms');
    }
}
