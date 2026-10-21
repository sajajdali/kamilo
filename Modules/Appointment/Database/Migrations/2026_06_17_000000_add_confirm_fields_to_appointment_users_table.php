<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfirmFieldsToAppointmentUsersTable extends Migration
{
    public function up()
    {
        Schema::table('appointment_users', function (Blueprint $table) {
            $table->boolean('is_confirmed')->default(false)->after('visit');
            $table->unsignedInteger('confirmed_by')->nullable()->after('is_confirmed');
            $table->timestamp('confirmed_at')->nullable()->after('confirmed_by');

            $table->index(['year', 'month', 'day', 'is_confirmed'], 'appointment_users_date_confirmed_index');
            $table->foreign('confirmed_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('appointment_users', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropIndex('appointment_users_date_confirmed_index');
            $table->dropColumn(['is_confirmed', 'confirmed_by', 'confirmed_at']);
        });
    }
}
