<?php
/**
 * PHP 8.x–compatible VoIP bridge for local XAMPP.
 * Mirrors Modules/Appointment/Http/Controllers/Voip/VoipAppointmentController
 * so the call-center can reach kamilo when full Laravel cannot boot on PHP 8.2.
 *
 * Base: {APP}/public/external-api/voip/v1/
 * Auth: Authorization: Bearer <token>  (selp_options.voip_integration)
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Voip-Bridge: kamilo-local');

const DIGITS = [
    '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
    '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
];

const STATUS = [
    0 => 'در انتظار', 1 => 'تایید شده', 2 => 'کنسل شده', 3 => 'حذف شده',
    4 => 'پناهی', 5 => 'کد معرف', 6 => 'تلفنی بدون وقت',
];

const KIND = [1 => 'اصلی', 0 => 'بین مریض'];
const CATEGORY = [1 => 'نوبت اولی', 2 => 'نوبت بازگشتی', 3 => 'نوبت ترمیمی', 4 => 'نوبت های افراد شاکی'];
const VISIT = [0 => 'در انتظار', 1 => 'ویزیت شده', 2 => 'عدم حضور بیمار'];
/** detail JSON key uses Arabic tatweel U+0640 in IDENTIFIERـCODE */
const IDENTIFIER_KEY = "IDENTIFIERـCODE";

try {
    $cfg = loadEnv(dirname(__DIR__, 4) . '/.env');
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', $cfg['DB_HOST'] ?? '127.0.0.1', $cfg['DB_PORT'] ?? '3306', $cfg['DB_DATABASE'] ?? 'laravel_kamilo'),
        $cfg['DB_USERNAME'] ?? 'root',
        $cfg['DB_PASSWORD'] ?? '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    $prefix = 'selp_';

    $auth = requireToken($pdo, $prefix);
    $path = trim((string) ($_GET['__path'] ?? ''), '/');
    unset($_GET['__path']);

    if ($path === 'me' || $path === '') {
        jsonOut(me($pdo, $prefix, $auth));
    }
    if ($path === 'appointments') {
        jsonOut(appointments($pdo, $prefix, $auth));
    }
    if (preg_match('#^patients/by-phone/(.+)$#', $path, $m)) {
        jsonOut(byPhone($pdo, $prefix, $auth, rawurldecode($m[1])));
    }
    if (preg_match('#^patients/(\d+)/dossier$#', $path, $m)) {
        jsonOut(dossierById($pdo, $prefix, $auth, (int) $m[1]));
    }
    http_response_code(404);
    jsonOut(['ok' => false, 'error' => 'not_found', 'path' => $path]);
} catch (Throwable $e) {
    $code = (int) $e->getCode();
    if ($code < 400 || $code > 599) {
        $code = 500;
    }
    http_response_code($code);
    jsonOut(['ok' => false, 'error' => $e->getMessage()]);
}

function jsonOut(array $data): void
{
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function loadEnv(string $file): array
{
    $out = [];
    if (!is_file($file)) {
        return $out;
    }
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $out[trim($k)] = trim($v, " \t\"'");
    }

    return $out;
}

function bearer(): string
{
    $h = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if (preg_match('/Bearer\s+(\S+)/i', $h, $m)) {
        return $m[1];
    }

    return '';
}

function requireToken(PDO $pdo, string $prefix): array
{
    $st = $pdo->prepare("SELECT value FROM {$prefix}options WHERE name = 'voip_integration' LIMIT 1");
    $st->execute();
    $raw = $st->fetchColumn();
    $saved = is_string($raw) ? json_decode($raw, true) : [];
    $saved = is_array($saved) ? $saved : [];
    $enabled = !empty($saved['enabled']);
    $token = (string) ($saved['token'] ?? '');
    $doctorId = isset($saved['doctor_id']) && $saved['doctor_id'] !== '' && $saved['doctor_id'] !== null
        ? (int) $saved['doctor_id'] : null;
    $given = bearer();
    if (!$enabled) {
        throw new RuntimeException('ارتباط با VoIP در تنظیمات غیرفعال است', 403);
    }
    if ($token === '' || $given === '' || !hash_equals($token, $given)) {
        throw new RuntimeException('توکن نامعتبر است', 401);
    }

    return ['doctor_id' => $doctorId];
}

function metaMap(PDO $pdo, string $prefix, int $userId): array
{
    $st = $pdo->prepare("SELECT meta_key, meta_value FROM {$prefix}user_metas WHERE user_id = ?");
    $st->execute([$userId]);
    $m = [];
    foreach ($st as $row) {
        $m[$row['meta_key']] = $row['meta_value'];
    }

    return $m;
}

function fullName(array $meta): string
{
    return trim(($meta['FIRST_NAME'] ?? '') . ' ' . ($meta['LAST_NAME'] ?? ''));
}

function normalizePhone(string $phone): string
{
    $phone = preg_replace('/\D/', '', strtr($phone, DIGITS));
    if (strpos($phone, '98') === 0 && strlen($phone) >= 12) {
        $phone = '0' . substr($phone, 2);
    }
    if (strpos($phone, '9') === 0 && strlen($phone) === 10) {
        $phone = '0' . $phone;
    }

    return $phone;
}

function voipOptionRow(PDO $pdo, string $prefix): array
{
    $st = $pdo->prepare("SELECT value FROM {$prefix}options WHERE name = 'voip_integration' LIMIT 1");
    $st->execute();
    $raw = $st->fetchColumn();
    $saved = is_string($raw) ? json_decode($raw, true) : [];

    return is_array($saved) ? $saved : [];
}

function me(PDO $pdo, string $prefix, array $auth): array
{
    $name = null;
    if ($auth['doctor_id']) {
        $name = fullName(metaMap($pdo, $prefix, (int) $auth['doctor_id']));
    }
    $saved = voipOptionRow($pdo, $prefix);

    return [
        'ok' => true,
        'provider' => 'kamilo',
        'doctor_id' => $auth['doctor_id'],
        'doctor_name' => $name !== '' ? $name : null,
        'scope' => $auth['doctor_id'] ? 'doctor' : 'all',
        'connection' => [
            'enabled' => !empty($saved['enabled']),
            'panel_url' => isset($saved['panel_url']) ? rtrim((string) $saved['panel_url'], '/') : '',
            'username' => (string) ($saved['username'] ?? ''),
            'has_password' => !empty($saved['password']),
        ],
    ];
}

function serviceFromDetail($detailJson, $confirmed): string
{
    $detail = is_string($detailJson) ? json_decode($detailJson, true) : [];
    $detail = is_array($detail) ? $detail : [];
    if ($confirmed) {
        return (string) $confirmed;
    }

    return (string) ($detail['SERVICE_DETAIL'] ?? $detail['SERVICE'] ?? '');
}

function appointments(PDO $pdo, string $prefix, array $auth): array
{
    $where = ['au.deleted_at IS NULL'];
    $params = [];
    if ($auth['doctor_id']) {
        $where[] = 'au.doctor_id = ?';
        $params[] = $auth['doctor_id'];
    }

    $date = $_GET['date'] ?? null;
    $from = $_GET['from'] ?? null;
    $to = $_GET['to'] ?? null;
    if ($date) {
        $p = jalaliParts($date);
        if (!$p) {
            throw new RuntimeException('تاریخ نامعتبر است (YYYY/MM/DD شمسی)', 422);
        }
        $where[] = 'au.year = ? AND au.month = ? AND au.day = ?';
        array_push($params, $p[0], $p[1], $p[2]);
    } elseif ($from || $to) {
        // simple numeric compare on year/month/day packed
        if ($from) {
            $p = jalaliParts($from);
            if ($p) {
                $where[] = '(au.year * 10000 + au.month * 100 + au.day) >= ?';
                $params[] = $p[0] * 10000 + $p[1] * 100 + $p[2];
            }
        }
        if ($to) {
            $p = jalaliParts($to);
            if ($p) {
                $where[] = '(au.year * 10000 + au.month * 100 + au.day) <= ?';
                $params[] = $p[0] * 10000 + $p[1] * 100 + $p[2];
            }
        }
    } else {
        // default today (approx via PHP Jalali-less: use latest year/month/day present — better: compute from DB max)
        // Use Iran wall-clock via Intl if available; else leave open and take recent
        $today = jalaliToday();
        $where[] = 'au.year = ? AND au.month = ? AND au.day = ?';
        array_push($params, $today[0], $today[1], $today[2]);
    }

    if (isset($_GET['confirmed']) && ($_GET['confirmed'] === '1' || $_GET['confirmed'] === 1)) {
        $where[] = 'au.is_confirmed = 1';
    }
    if (isset($_GET['status']) && is_numeric($_GET['status'])) {
        $where[] = 'au.status = ?';
        $params[] = (int) $_GET['status'];
    }

    $limit = min(500, max(1, (int) ($_GET['limit'] ?? 200)));
    $sql = "SELECT au.*, ap.title AS part_title
            FROM {$prefix}appointment_users au
            LEFT JOIN {$prefix}appointment_parts ap ON ap.id = au.appointment_part_id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY au.year, au.month, au.day, COALESCE(au.confirmed_time_from, au.time_from), au.id
            LIMIT {$limit}";
    $st = $pdo->prepare($sql);
    $st->execute($params);
    $rows = $st->fetchAll();
    $out = [];
    foreach ($rows as $a) {
        $out[] = formatAppointment($pdo, $prefix, $a);
    }

    return [
        'ok' => true,
        'provider' => 'kamilo',
        'doctor_id' => $auth['doctor_id'],
        'count' => count($out),
        'appointments' => $out,
    ];
}

function byPhone(PDO $pdo, string $prefix, array $auth, string $phone): array
{
    $phone = normalizePhone($phone);
    if (!preg_match('/^09\d{9}$/', $phone)) {
        throw new RuntimeException('شماره موبایل نامعتبر است', 422);
    }
    $st = $pdo->prepare("SELECT id, mobile FROM {$prefix}users WHERE mobile = ? ORDER BY id LIMIT 1");
    $st->execute([$phone]);
    $user = $st->fetch();
    if (!$user) {
        return emptyDossier($phone, $auth['doctor_id']);
    }

    return buildDossier($pdo, $prefix, $auth, (int) $user['id'], $phone);
}

function dossierById(PDO $pdo, string $prefix, array $auth, int $id): array
{
    $st = $pdo->prepare("SELECT id, mobile FROM {$prefix}users WHERE id = ? LIMIT 1");
    $st->execute([$id]);
    $user = $st->fetch();
    if (!$user) {
        return emptyDossier('', $auth['doctor_id'], $id);
    }
    $phone = normalizePhone((string) ($user['mobile'] ?? ''));

    return buildDossier($pdo, $prefix, $auth, (int) $user['id'], $phone);
}

function fieldGaps(): array
{
    return [
        'province' => 'No dedicated province column; user_metas.ADDRESS is city-like text (sometimes "city/province").',
        'dossier_notes' => 'No patient dossier notes table/column; stub only. Per-appointment notes live in appointment_users.confirmed_comment.',
        'city' => 'Mapped from user_metas.ADDRESS (meta_key=ADDRESS), not a column on users.',
    ];
}

function emptyDossier(string $phone, $doctorId, $requestedId = null): array
{
    return [
        'ok' => true,
        'found' => false,
        'provider' => 'kamilo',
        'id' => $requestedId,
        'name' => '',
        'phone' => $phone,
        'doctor_id' => $doctorId,
        'appointments_total' => 0,
        'appointments_received_count' => 0,
        'appointments_active_count' => 0,
        'appointments_cancelled_count' => 0,
        'appointments_in_person' => 0,
        'appointments_online' => 0,
        'next_appointment' => null,
        'last_received_appointment' => null,
        'summary' => null,
        'patient' => null,
        'appointments' => [],
        'in_person_appointments' => [],
        'online_appointments' => [],
        'dossier_notes' => null,
        'dossier_notes_supported' => false,
        'dossier_notes_note' => 'No patient-level dossier notes column/table in kamilo (only per-appointment confirmed_comment).',
        'field_gaps' => fieldGaps(),
    ];
}

function splitCityProvince(string $address): array
{
    $address = trim($address);
    if ($address === '') {
        return [null, null];
    }
    foreach ([' / ', '/', ' - ', '،', ','] as $sep) {
        if (strpos($address, $sep) !== false) {
            $parts = array_map('trim', explode($sep, $address, 2));

            return [
                $parts[0] !== '' ? $parts[0] : null,
                isset($parts[1]) && $parts[1] !== '' ? $parts[1] : null,
            ];
        }
    }

    return [$address, null];
}

function isTerminalStatus(int $status): bool
{
    return $status === 2 || $status === 3;
}

function agentName(PDO $pdo, string $prefix, $agentId): ?string
{
    if (!$agentId) {
        return null;
    }
    $n = fullName(metaMap($pdo, $prefix, (int) $agentId));

    return $n !== '' ? $n : null;
}

function formatDossierAppointment(PDO $pdo, string $prefix, array $a): array
{
    $detail = is_string($a['detail'] ?? null) ? json_decode($a['detail'], true) : [];
    $detail = is_array($detail) ? $detail : [];
    $statusVal = (int) $a['status'];
    $kindVal = ((int) ($a['kind'] ?? 1) === 1) ? 1 : 0;
    $catRaw = $detail['APPOINTMENT_FIRST_TYPE'] ?? null;
    $cat = ($catRaw !== null && $catRaw !== '' && is_numeric($catRaw)) ? (int) $catRaw : null;
    $identifier = trim((string) ($detail[IDENTIFIER_KEY] ?? ''));
    $second = trim((string) ($detail['SECOND_MOBILE'] ?? ''));
    $jalali = sprintf('%04d/%02d/%02d', $a['year'], $a['month'], $a['day']);
    $time = substr((string) (($a['confirmed_time_from'] ?: $a['time_from']) ?? ''), 0, 5);
    $visit = (int) ($a['visit'] ?? 0);
    if ($statusVal === 2) {
        $kindStatusLabel = 'کنسل شده';
    } elseif ($kindVal === 0) {
        $kindStatusLabel = 'بین مریض';
    } else {
        $kindStatusLabel = 'اصلی';
    }
    $did = (int) ($a['doctor_id'] ?? 0);
    $dm = $did ? metaMap($pdo, $prefix, $did) : [];

    return [
        'id' => (int) $a['id'],
        'tracking_code' => (string) ($a['code'] ?? ''),
        'code' => (string) ($a['code'] ?? ''),
        'date' => $jalali,
        'time' => $time,
        'time_from' => substr((string) ($a['time_from'] ?? ''), 0, 5),
        'time_to' => substr((string) ($a['time_to'] ?? ''), 0, 5),
        'service' => serviceFromDetail($a['detail'] ?? '', $a['confirmed_service_name'] ?? null) ?: (string) ($a['part_title'] ?? ''),
        'part' => (string) ($a['part_title'] ?? ''),
        'type' => (string) ($a['type'] ?? ''),
        'kind' => $kindVal === 1 ? 'main' : 'subsidiary',
        'kind_label' => KIND[$kindVal] ?? (string) $kindVal,
        'kind_status_label' => $kindStatusLabel,
        'status' => $statusVal,
        'status_label' => STATUS[$statusVal] ?? (string) $statusVal,
        'is_confirmed' => !empty($a['is_confirmed']),
        'is_confirmed_label' => !empty($a['is_confirmed']) ? 'confirm شده' : 'confirm نشده',
        'appointment_category' => $cat,
        'appointment_category_label' => $cat !== null ? (CATEGORY[$cat] ?? (string) $cat) : null,
        'is_first_appointment' => $cat === 1,
        'identifier_code' => $identifier !== '' ? $identifier : null,
        'second_mobile' => $second !== '' ? $second : null,
        'agent_id' => !empty($a['agent_id']) ? (int) $a['agent_id'] : null,
        'agent_name' => agentName($pdo, $prefix, $a['agent_id'] ?? null),
        'doctor' => ['id' => $did ?: null, 'name' => fullName($dm) ?: null],
        'visit' => $visit,
        'visit_label' => VISIT[$visit] ?? (string) $visit,
        'amount_paid' => $a['cost'] ?? null,
        'confirmed_comment' => (isset($a['confirmed_comment']) && $a['confirmed_comment'] !== '')
            ? (string) $a['confirmed_comment'] : null,
        'created_at' => $a['created_at'] ?? null,
        'updated_at' => $a['updated_at'] ?? null,
    ];
}

function buildDossier(PDO $pdo, string $prefix, array $auth, int $uid, string $phone): array
{
    $meta = metaMap($pdo, $prefix, $uid);
    $where = ['au.deleted_at IS NULL', 'au.user_id = ?'];
    $params = [$uid];
    if ($auth['doctor_id']) {
        $where[] = 'au.doctor_id = ?';
        $params[] = $auth['doctor_id'];
    }
    $sql = "SELECT au.*, ap.title AS part_title
            FROM {$prefix}appointment_users au
            LEFT JOIN {$prefix}appointment_parts ap ON ap.id = au.appointment_part_id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY au.year DESC, au.month DESC, au.day DESC, au.id DESC
            LIMIT 400";
    $st = $pdo->prepare($sql);
    $st->execute($params);
    $rows = $st->fetchAll();

    $dossierAppts = [];
    $inPerson = [];
    $online = [];
    foreach ($rows as $a) {
        $rich = formatDossierAppointment($pdo, $prefix, $a);
        $dossierAppts[] = $rich;
        $item = formatCrmRow($a, $rich);
        $type = strtoupper((string) ($a['type'] ?? ''));
        if (strpos($type, 'ONLINE') !== false || strpos($type, 'آنلاین') !== false) {
            $online[] = $item;
        } else {
            $inPerson[] = $item;
        }
    }

    $today = jalaliToday();
    $todayKey = $today[0] * 10000 + $today[1] * 100 + $today[2];
    $nextModel = null;
    $lastReceivedModel = null;
    $sorted = $rows;
    usort($sorted, function ($a, $b) {
        $ka = sprintf('%04d%02d%02d%s', $a['year'], $a['month'], $a['day'], substr((string) $a['time_from'], 0, 5));
        $kb = sprintf('%04d%02d%02d%s', $b['year'], $b['month'], $b['day'], substr((string) $b['time_from'], 0, 5));

        return strcmp($ka, $kb);
    });
    foreach ($sorted as $a) {
        if (isTerminalStatus((int) $a['status'])) {
            continue;
        }
        $key = (int) $a['year'] * 10000 + (int) $a['month'] * 100 + (int) $a['day'];
        if ($key >= $todayKey) {
            $nextModel = $a;
            break;
        }
    }
    foreach ($rows as $a) {
        if (isTerminalStatus((int) $a['status'])) {
            continue;
        }
        $key = (int) $a['year'] * 10000 + (int) $a['month'] * 100 + (int) $a['day'];
        if ($key < $todayKey) {
            $lastReceivedModel = $a;
            break;
        }
    }

    $activeCount = 0;
    $cancelledCount = 0;
    $receivedCount = 0;
    foreach ($rows as $a) {
        $stt = (int) $a['status'];
        if ($stt === 2) {
            $cancelledCount++;
        }
        if (!isTerminalStatus($stt)) {
            $activeCount++;
            $receivedCount++;
        }
    }

    $focus = $nextModel ?: ($rows[0] ?? null);
    $address = trim((string) ($meta['ADDRESS'] ?? ''));
    [$city, $province] = splitCityProvince($address);
    $secondMobile = null;
    if ($focus) {
        $fd = is_string($focus['detail'] ?? null) ? json_decode($focus['detail'], true) : [];
        $fd = is_array($fd) ? $fd : [];
        $fromDetail = trim((string) ($fd['SECOND_MOBILE'] ?? ''));
        if ($fromDetail !== '') {
            $secondMobile = $fromDetail;
        }
    }
    if ($secondMobile === null) {
        $fromUser = trim((string) ($meta['SECOND_MOBILE'] ?? ''));
        $secondMobile = $fromUser !== '' ? $fromUser : null;
    }

    $name = fullName($meta);
    $next = $nextModel ? formatDossierAppointment($pdo, $prefix, $nextModel) : null;
    $lastReceived = $lastReceivedModel ? formatDossierAppointment($pdo, $prefix, $lastReceivedModel) : null;

    $summary = null;
    if ($focus) {
        $f = formatDossierAppointment($pdo, $prefix, $focus);
        $summary = [
            'full_name' => $name,
            'agent_name' => $f['agent_name'],
            'agent_id' => $f['agent_id'],
            'is_first_appointment' => $f['is_first_appointment'],
            'is_first_appointment_label' => $f['is_first_appointment'] ? 'بله' : 'خیر',
            'appointments_received_count' => $receivedCount,
            'mobile' => $phone,
            'second_mobile' => $secondMobile,
            'part' => $f['part'],
            'doctor' => $f['doctor'],
            'next_appointment_at' => $next ? ['date' => $next['date'], 'time' => $next['time'], 'id' => $next['id']] : null,
            'last_received_appointment_at' => $lastReceived
                ? ['date' => $lastReceived['date'], 'time' => $lastReceived['time'], 'id' => $lastReceived['id']]
                : null,
            'last_received_created_at' => $lastReceived['created_at'] ?? null,
            'appointment_kind' => $f['kind'],
            'appointment_kind_label' => $f['kind_label'],
            'status' => $f['status'],
            'status_label' => $f['status_label'],
            'is_confirmed' => $f['is_confirmed'],
            'is_confirmed_label' => $f['is_confirmed'] ? 'confirm شده' : 'confirm نشده',
            'city' => $city,
            'province' => $province,
            'address' => $address !== '' ? $address : null,
            'appointment_category' => $f['appointment_category'],
            'appointment_category_label' => $f['appointment_category_label'],
            'identifier_code' => $f['identifier_code'],
            'tracking_code' => $f['tracking_code'],
            'appointment_id' => $f['id'],
            'appointments_active_count' => $activeCount,
            'appointments_cancelled_count' => $cancelledCount,
            'dossier_notes' => null,
        ];
    }

    return [
        'ok' => true,
        'found' => true,
        'provider' => 'kamilo',
        'id' => $uid,
        'name' => $name,
        'first_name' => (string) ($meta['FIRST_NAME'] ?? ''),
        'last_name' => (string) ($meta['LAST_NAME'] ?? ''),
        'phone' => $phone,
        'national_code' => (string) ($meta['NATIONAL_CODE'] ?? ''),
        'file_no' => (string) ($meta['MEDICAL_RECORD_NUMBER'] ?? ''),
        'second_mobile' => $secondMobile,
        'city' => $city,
        'province' => $province,
        'address' => $address !== '' ? $address : null,
        'appointments_total' => count($rows),
        'appointments_received_count' => $receivedCount,
        'appointments_active_count' => $activeCount,
        'appointments_cancelled_count' => $cancelledCount,
        'appointments_in_person' => count($inPerson),
        'appointments_online' => count($online),
        'next_appointment' => $next,
        'last_received_appointment' => $lastReceived,
        'summary' => $summary,
        'patient' => [
            'id' => $uid,
            'name' => $name,
            'first_name' => (string) ($meta['FIRST_NAME'] ?? ''),
            'last_name' => (string) ($meta['LAST_NAME'] ?? ''),
            'mobile' => $phone,
            'second_mobile' => $secondMobile,
            'national_code' => (string) ($meta['NATIONAL_CODE'] ?? ''),
            'file_no' => (string) ($meta['MEDICAL_RECORD_NUMBER'] ?? ''),
            'city' => $city,
            'province' => $province,
            'address' => $address !== '' ? $address : null,
        ],
        'appointments' => $dossierAppts,
        'in_person_appointments' => $inPerson,
        'online_appointments' => $online,
        'dossier_notes' => null,
        'dossier_notes_supported' => false,
        'dossier_notes_note' => 'No patient-level dossier notes column/table in kamilo (only per-appointment confirmed_comment).',
        'field_gaps' => fieldGaps(),
        'doctor_id' => $auth['doctor_id'],
    ];
}

function formatAppointment(PDO $pdo, string $prefix, array $a): array
{
    $uid = (int) ($a['user_id'] ?? 0);
    $did = (int) ($a['doctor_id'] ?? 0);
    $um = $uid ? metaMap($pdo, $prefix, $uid) : [];
    $dm = $did ? metaMap($pdo, $prefix, $did) : [];
    $mobile = '';
    if ($uid) {
        $st = $pdo->prepare("SELECT mobile FROM {$prefix}users WHERE id = ? LIMIT 1");
        $st->execute([$uid]);
        $mobile = (string) ($st->fetchColumn() ?: '');
    }
    $statusVal = (int) $a['status'];
    $timeFrom = substr((string) (($a['confirmed_time_from'] ?: $a['time_from']) ?? ''), 0, 5);
    $jalali = sprintf('%04d/%02d/%02d', $a['year'], $a['month'], $a['day']);

    return [
        'id' => (int) $a['id'],
        'code' => $a['code'],
        'date' => $jalali,
        'jalali' => $jalali,
        'time' => $timeFrom,
        'time_from' => substr((string) $a['time_from'], 0, 5),
        'time_to' => substr((string) $a['time_to'], 0, 5),
        'service' => serviceFromDetail($a['detail'] ?? '', $a['confirmed_service_name'] ?? null),
        'part' => $a['part_title'] ?? null,
        'type' => (string) ($a['type'] ?? ''),
        'kind' => ((int) ($a['kind'] ?? 1) === 1) ? 'main' : 'subsidiary',
        'status' => $statusVal,
        'status_label' => STATUS[$statusVal] ?? (string) $statusVal,
        'is_confirmed' => !empty($a['is_confirmed']),
        'doctor' => ['id' => $did ?: null, 'name' => fullName($dm)],
        'patient' => [
            'id' => $uid ?: null,
            'name' => fullName($um),
            'first_name' => (string) ($um['FIRST_NAME'] ?? ''),
            'last_name' => (string) ($um['LAST_NAME'] ?? ''),
            'mobile' => $mobile,
            'national_code' => (string) ($um['NATIONAL_CODE'] ?? ''),
            'file_no' => (string) ($um['MEDICAL_RECORD_NUMBER'] ?? ''),
        ],
    ];
}

function formatCrmRow(array $a, ?array $rich = null): array
{
    if ($rich === null) {
        $statusVal = (int) $a['status'];
        $jalali = sprintf('%04d/%02d/%02d', $a['year'], $a['month'], $a['day']);

        return [
            'id' => (int) $a['id'],
            'date' => $jalali,
            'jalali' => $jalali,
            'time' => substr((string) (($a['confirmed_time_from'] ?: $a['time_from']) ?? ''), 0, 5),
            'service' => serviceFromDetail($a['detail'] ?? '', $a['confirmed_service_name'] ?? null),
            'place' => (string) ($a['part_title'] ?? ''),
            'status' => $statusVal,
            'status_label' => STATUS[$statusVal] ?? (string) $statusVal,
            'amount_paid' => $a['cost'] ?? null,
        ];
    }

    return [
        'id' => $rich['id'],
        'date' => $rich['date'],
        'jalali' => $rich['date'],
        'time' => $rich['time'],
        'service' => $rich['service'],
        'place' => $rich['part'],
        'status' => $rich['status'],
        'status_label' => $rich['status_label'],
        'amount_paid' => $rich['amount_paid'],
    ];
}

function jalaliParts($s): ?array
{
    $s = strtr((string) $s, DIGITS);
    $s = str_replace('-', '/', trim($s));
    if (!preg_match('/^(\d{4})\/(\d{1,2})\/(\d{1,2})$/', $s, $m)) {
        return null;
    }

    return [(int) $m[1], (int) $m[2], (int) $m[3]];
}

/** Approximate Jalali today via Intl (Asia/Tehran). Fallback: 1404/01/01. */
function jalaliToday(): array
{
    try {
        if (class_exists('IntlDateFormatter')) {
            $fmt = new IntlDateFormatter('fa_IR@calendar=persian', IntlDateFormatter::NONE, IntlDateFormatter::NONE, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL, 'yyyy/MM/dd');
            $s = $fmt->format(new DateTime('now', new DateTimeZone('Asia/Tehran')));
            $s = strtr((string) $s, DIGITS);
            $p = jalaliParts($s);
            if ($p) {
                return $p;
            }
        }
    } catch (Throwable $e) {
        // fall through
    }
    // DB-less fallback: pick a recent known Jalali year from calendar conversion
    $g = new DateTime('now', new DateTimeZone('Asia/Tehran'));

    return gregorianToJalali((int) $g->format('Y'), (int) $g->format('m'), (int) $g->format('d'));
}

function gregorianToJalali(int $gy, int $gm, int $gd): array
{
    $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    $gy2 = $gm > 2 ? $gy + 1 : $gy;
    $days = 355666 + (365 * $gy) + intdiv($gy2 + 3, 4) - intdiv($gy2 + 99, 100) + intdiv($gy2 + 399, 400) + $gd + $g_d_m[$gm - 1];
    $jy = -1595 + 33 * intdiv($days, 12053);
    $days %= 12053;
    $jy += 4 * intdiv($days, 1461);
    $days %= 1461;
    if ($days > 365) {
        $jy += intdiv($days - 1, 365);
        $days = ($days - 1) % 365;
    }
    $jm = $days < 186 ? 1 + intdiv($days, 31) : 7 + intdiv($days - 186, 30);
    $jd = 1 + ($days < 186 ? $days % 31 : ($days - 186) % 30);

    return [$jy, $jm, $jd];
}
