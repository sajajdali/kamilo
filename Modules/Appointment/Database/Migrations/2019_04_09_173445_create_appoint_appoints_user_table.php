<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointAppointsUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('agent_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('transaction_id')->nullable();
            $table->unsignedInteger('appointment_part_id')->nullable();
            $table->unsignedInteger('doctor_id')->nullable();
            $table->char('code',255);
            $table->double('cost');
            $table->double('percent');
            $table->char('type',64)->comment('نوع نوبت (حضوری - آنلاین یا ...)');
            $table->integer('day');
            $table->integer('month');
            $table->integer('year');
            $table->time('time_from');
            $table->time('time_to');
            $table->ipAddress('ip');
            $table->boolean('status')->comment('نوبت در انتظار - تایید شده یا کنسل شده');
            $table->boolean('kind')->comment('نوبت اصلی یا بین مریض');
            $table->tinyInteger('visit')->comment('وضعیت ویزیت');
            $table->text('r_part')->comment('زیر بخش‌های مرتبط');
            $table->text('detail');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('agent_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->foreign('doctor_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->foreign('appointment_part_id')
                ->references('id')
                ->on('appointment_parts')
                ->onDelete('set null');
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_users');
    }
}
