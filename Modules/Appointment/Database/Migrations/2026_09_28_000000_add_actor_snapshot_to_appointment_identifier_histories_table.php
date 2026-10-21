<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddActorSnapshotToAppointmentIdentifierHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('appointment_identifier_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('appointment_identifier_histories', 'changed_by_name')) {
                $table->string('changed_by_name')->nullable()->after('changed_by');
            }
            if (!Schema::hasColumn('appointment_identifier_histories', 'changed_by_mobile')) {
                $table->string('changed_by_mobile', 30)->nullable()->after('changed_by_name');
            }
            if (!Schema::hasColumn('appointment_identifier_histories', 'ip')) {
                $table->string('ip', 45)->nullable()->after('changed_by_mobile');
            }
        });

        DB::table('appointment_identifier_histories')
            ->whereNotNull('changed_by')
            ->orderBy('id')
            ->chunk(200, function ($histories) {
                $users = DB::table('users')
                    ->whereIn('id', $histories->pluck('changed_by')->filter()->unique()->values()->all())
                    ->get()
                    ->keyBy('id');

                foreach ($histories as $history) {
                    $user = $users->get($history->changed_by);
                    if (!$user) {
                        continue;
                    }

                    $firstName = DB::table('user_metas')->where('user_id', $user->id)->where('meta_key', 'FIRST_NAME')->value('meta_value');
                    $lastName = DB::table('user_metas')->where('user_id', $user->id)->where('meta_key', 'LAST_NAME')->value('meta_value');
                    $name = trim($firstName . ' ' . $lastName);

                    DB::table('appointment_identifier_histories')->where('id', $history->id)->update([
                        'changed_by_name' => $name !== '' ? $name : null,
                        'changed_by_mobile' => $user->mobile ?: null,
                    ]);
                }
            });
    }

    public function down()
    {
        Schema::table('appointment_identifier_histories', function (Blueprint $table) {
            $table->dropColumn(['changed_by_name', 'changed_by_mobile', 'ip']);
        });
    }
}
