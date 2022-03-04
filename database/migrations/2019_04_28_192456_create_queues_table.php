<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->tinyInteger('type')->comment('نوع صف که می‌تواند ایمیل یا پیامک یا نوتیفیکشن باشد');
            $table->text("to");
            $table->text("content");
            $table->text("result")->nullable()->comment('نتیجه ارسال');
            $table->string('model')->nullable()->comment('نام مدل');
            $table->dateTime("send_at");
            $table->integer("priority");
            $table->boolean('status')->comment('وضعیت ارسال شده یا ارسال نشده');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queues');
    }
}
