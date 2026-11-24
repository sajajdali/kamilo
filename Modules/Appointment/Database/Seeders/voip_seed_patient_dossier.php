<?php
/**
 * One-shot seed for VoIP dossier demo patient 09121498902 (user 61097, doctor 29).
 * Run: php Modules/Appointment/Database/Seeders/voip_seed_patient_dossier.php
 */
declare(strict_types=1);

$pdo = new PDO('mysql:host=127.0.0.1;dbname=laravel_kamilo;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
$pdo->exec('SET NAMES utf8mb4');

$uid = 61097;
$doctorId = 29;
$agentMain = 61513; // ریحانه داوودی
$agentAlt = 193;
$partId = 96;
$partAlt = 116;
$identKey = "IDENTIFIERـCODE"; // Arabic tatweel U+0640
$now = date('Y-m-d H:i:s');

function upsertMeta(PDO $pdo, int $uid, string $key, string $val): void
{
    $st = $pdo->prepare('SELECT id FROM selp_user_metas WHERE user_id=? AND meta_key=? LIMIT 1');
    $st->execute([$uid, $key]);
    $id = $st->fetchColumn();
    if ($id) {
        $u = $pdo->prepare('UPDATE selp_user_metas SET meta_value=?, updated_at=NOW() WHERE id=?');
        $u->execute([$val, $id]);
    } else {
        $i = $pdo->prepare('INSERT INTO selp_user_metas (user_id, meta_key, meta_value, created_at, updated_at) VALUES (?,?,?,NOW(),NOW())');
        $i->execute([$uid, $key, $val]);
    }
}

function loadDetail(PDO $pdo, int $id): array
{
    $st = $pdo->prepare('SELECT detail FROM selp_appointment_users WHERE id=?');
    $st->execute([$id]);
    $raw = $st->fetchColumn();
    $d = is_string($raw) ? json_decode($raw, true) : [];

    return is_array($d) ? $d : [];
}

function encodeDetail(array $d): string
{
    return json_encode($d, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

$pdo->beginTransaction();
try {
    upsertMeta($pdo, $uid, 'ADDRESS', 'تهران / تهران');
    upsertMeta($pdo, $uid, 'SECOND_MOBILE', '09120001122');
    upsertMeta($pdo, $uid, 'MEDICAL_RECORD_NUMBER', 'PR-61097');
    upsertMeta($pdo, $uid, 'DOSSIER_NOTES', 'یادداشت پرونده تست ویپ: حساسیت به لیدوکائین — پیگیری کد معرف VOIP-SEED-PARISA');
    upsertMeta($pdo, $uid, 'NATIONAL_CODE', '0012345678');

    // 186805 — past received (آخرین گذشته): ترمیمی + اصلی + confirm + کد معرف
    $d = loadDetail($pdo, 186805);
    $d[$identKey] = 'VOIP-SEED-PARISA';
    $d['APPOINTMENT_FIRST_TYPE'] = '3';
    $d['SECOND_MOBILE'] = '09120001122';
    $d['SERVICE'] = 'مشاوره لیفت صورت';
    $pdo->prepare(
        "UPDATE selp_appointment_users SET detail=?, agent_id=?, kind=1, status=1, is_confirmed=1,
         confirmed_at=?, confirmed_by=?, confirmed_time_from='09:00:00',
         confirmed_service_name='مشاوره لیفت صورت', confirmed_comment=?, updated_at=? WHERE id=186805"
    )->execute([encodeDetail($d), $agentMain, $now, $agentMain, 'ویزیت گذشته — تایید برای تست پرونده', $now]);

    // 186191 — بین مریض + confirmed + بازگشتی
    $d = loadDetail($pdo, 186191);
    $d[$identKey] = 'زیبایار';
    $d['APPOINTMENT_FIRST_TYPE'] = '2';
    $d['SECOND_MOBILE'] = '09120001122';
    $pdo->prepare(
        "UPDATE selp_appointment_users SET detail=?, agent_id=?, kind=0, status=1, is_confirmed=1,
         confirmed_at=?, confirmed_by=?, confirmed_comment=?, updated_at=? WHERE id=186191"
    )->execute([encodeDetail($d), $agentAlt, $now, $agentAlt, 'نوبت بین مریض تست', $now]);

    // 181756 — نوبت اولی (first visit) + کد معرف
    $d = loadDetail($pdo, 181756);
    $d[$identKey] = 'drrezatami';
    $d['APPOINTMENT_FIRST_TYPE'] = '1';
    $d['SECOND_MOBILE'] = '09120001122';
    $pdo->prepare(
        "UPDATE selp_appointment_users SET detail=?, agent_id=?, kind=1, status=1, is_confirmed=0,
         confirmed_comment=?, updated_at=? WHERE id=181756"
    )->execute([encodeDetail($d), $agentAlt, 'اولین نوبت بیمار', $now]);

    // Soft-delete any prior seed rows we may have created (by tracking code prefix)
    $pdo->prepare(
        "UPDATE selp_appointment_users SET deleted_at=? WHERE user_id=? AND doctor_id=? AND code LIKE 'SEED29%' AND deleted_at IS NULL"
    )->execute([$now, $uid, $doctorId]);

    // Future next — 1405/09/20 (today is 1405/09/03): اصلی + بازگشتی + confirm + کد معرف
    $detailFuture = encodeDetail([
        'SHORT_LINK' => 'https://kamilo.ir/appointment/result/SEED29FUTURE/0',
        $identKey => 'VOIP-SEED-PARISA',
        'APPOINTMENT_FIRST_TYPE' => '2',
        'SECOND_MOBILE' => '09120001122',
        'SERVICE' => 'مشاوره لیفت صورت',
    ]);
    $pdo->prepare(
        "INSERT INTO selp_appointment_users
        (agent_id, user_id, transaction_id, appointment_part_id, doctor_id, code, cost, percent, type,
         day, month, year, time_from, time_to, ip, status, kind, visit, is_confirmed, confirmed_by, confirmed_at,
         confirmed_time_from, confirmed_service_name, confirmed_comment, r_part, detail, created_at, updated_at, deleted_at)
         VALUES (?,?,NULL,?,?, 'SEED29FUTURE001', 0, 0, 'IN_PERSON',
         20, 9, 1405, '11:00:00', '11:15:00', '127.0.0.1', 1, 1, 0, 1, ?, ?,
         '11:00:00', 'مشاوره لیفت صورت', 'نوبت بعدی تست ویپ', '[]', ?, ?, ?, NULL)"
    )->execute([$agentMain, $uid, $partId, $doctorId, $agentMain, $now, $detailFuture, $now, $now]);
    $futureId = (int) $pdo->lastInsertId();
    $futureCode = 'SEED29FUTURE' . $futureId;
    $detailFutureArr = json_decode($detailFuture, true);
    $detailFutureArr['SHORT_LINK'] = 'https://kamilo.ir/appointment/result/' . $futureCode . '/' . $futureId;
    $pdo->prepare('UPDATE selp_appointment_users SET code=?, detail=? WHERE id=?')
        ->execute([$futureCode, encodeDetail($detailFutureArr), $futureId]);

    // Cancelled — 1405/07/10: کنسل شده + افراد شاکی category
    $detailCancel = encodeDetail([
        'SHORT_LINK' => 'https://kamilo.ir/appointment/result/SEED29CANCEL/0',
        $identKey => 'VOIP-SEED-PARISA',
        'APPOINTMENT_FIRST_TYPE' => '4',
        'SECOND_MOBILE' => '09120001122',
        'SERVICE' => 'مشاوره لیفت صورت',
    ]);
    $pdo->prepare(
        "INSERT INTO selp_appointment_users
        (agent_id, user_id, transaction_id, appointment_part_id, doctor_id, code, cost, percent, type,
         day, month, year, time_from, time_to, ip, status, kind, visit, is_confirmed, confirmed_by, confirmed_at,
         confirmed_time_from, confirmed_service_name, confirmed_comment, r_part, detail, created_at, updated_at, deleted_at)
         VALUES (?,?,NULL,?,?, 'SEED29CANCEL001', 0, 0, 'IN_PERSON',
         10, 7, 1405, '16:00:00', '16:15:00', '127.0.0.1', 2, 1, 0, 0, NULL, NULL,
         NULL, NULL, 'کنسل شده برای تست شمارنده', '[]', ?, ?, ?, NULL)"
    )->execute([$agentAlt, $uid, $partAlt ?: $partId, $doctorId, $detailCancel, $now, $now]);
    $cancelId = (int) $pdo->lastInsertId();
    $cancelCode = 'SEED29CANCEL' . $cancelId;
    $detailCancelArr = json_decode($detailCancel, true);
    $detailCancelArr['SHORT_LINK'] = 'https://kamilo.ir/appointment/result/' . $cancelCode . '/' . $cancelId;
    $pdo->prepare('UPDATE selp_appointment_users SET code=?, detail=? WHERE id=?')
        ->execute([$cancelCode, encodeDetail($detailCancelArr), $cancelId]);

    $pdo->commit();
    echo "OK seeded user={$uid} future_id={$futureId} cancel_id={$cancelId}\n";
} catch (Throwable $e) {
    $pdo->rollBack();
    fwrite(STDERR, 'SEED FAIL: ' . $e->getMessage() . "\n");
    exit(1);
}

$st = $pdo->prepare(
    "SELECT id, status, kind, is_confirmed, year, month, day, code,
            JSON_UNQUOTE(JSON_EXTRACT(detail, '$.APPOINTMENT_FIRST_TYPE')) cat,
            JSON_UNQUOTE(JSON_EXTRACT(detail, CONCAT('$.\"', ?, '\"'))) ident
     FROM selp_appointment_users
     WHERE user_id=? AND doctor_id=? AND deleted_at IS NULL
     ORDER BY year DESC, month DESC, day DESC, id DESC"
);
$st->execute([$identKey, $uid, $doctorId]);
foreach ($st as $row) {
    echo sprintf(
        "%d %04d/%02d/%02d st=%s kind=%s conf=%s cat=%s id=%s code=%s\n",
        $row['id'],
        $row['year'],
        $row['month'],
        $row['day'],
        $row['status'],
        $row['kind'],
        $row['is_confirmed'],
        $row['cat'],
        $row['ident'],
        $row['code']
    );
}
