<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentIdentifierHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('appointment_identifier_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('appointment_user_id');
            $table->unsignedInteger('changed_by')->nullable();
            $table->string('changed_by_name')->nullable();
            $table->string('changed_by_mobile', 30)->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('action', 20);
            $table->string('old_value', 50)->nullable();
            $table->string('new_value', 50)->nullable();
            $table->timestamps();

            $table->index(['appointment_user_id', 'created_at'], 'appointment_identifier_history_lookup');
            $table->foreign('appointment_user_id')
                ->references('id')->on('appointment_users')->onDelete('cascade');
            $table->foreign('changed_by')
                ->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointment_identifier_histories');
    }
}
