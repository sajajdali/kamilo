<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfirmedListFieldsToAppointmentUsersTable extends Migration
{
    public function up()
    {
        Schema::table('appointment_users', function (Blueprint $table) {
            $table->time('confirmed_time_from')->nullable()->after('confirmed_at');
            $table->string('confirmed_service_name')->nullable()->after('confirmed_time_from');
            $table->text('confirmed_comment')->nullable()->after('confirmed_service_name');
        });
    }

    public function down()
    {
        Schema::table('appointment_users', function (Blueprint $table) {
            $table->dropColumn(['confirmed_time_from', 'confirmed_service_name', 'confirmed_comment']);
        });
    }
}
