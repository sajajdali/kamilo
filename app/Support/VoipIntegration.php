<?php

namespace App\Support;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/**
 * «ارتباط با VoIP»: Bearer token for the call-center panel (voip.s8n.ir).
 * Stored in options as voip_integration. Optional doctor_id scopes appointments
 * to that doctor (User id); null = all doctors on this kamilo install.
 *
 * Token format: km_<doctorIdOr0>.<secret> — VoIP only needs URL + token.
 */
class VoipIntegration
{
    const OPTION = 'voip_integration';

    public static function settings(): array
    {
        $raw = get_option(self::OPTION, false);
        $saved = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);
        $saved = is_array($saved) ? $saved : [];

        return [
            'enabled' => (bool) ($saved['enabled'] ?? false),
            'token' => self::decrypt($saved['token'] ?? ''),
            'doctor_id' => isset($saved['doctor_id']) && $saved['doctor_id'] !== '' && $saved['doctor_id'] !== null
                ? (int) $saved['doctor_id']
                : null,
            'created_at' => (string) ($saved['created_at'] ?? ''),
        ];
    }

    public static function setEnabled(bool $enabled): array
    {
        $c = self::settings();
        self::store($enabled, $c['token'], $c['doctor_id'], $c['created_at']);

        return self::settings();
    }

    /**
     * New token. Pass doctor_id to scope to that doctor; omit/null for all.
     */
    public static function generate(?int $doctorId = null): array
    {
        $scope = $doctorId && $doctorId > 0 ? $doctorId : 0;
        $token = 'km_' . $scope . '.' . Str::random(48);
        self::store(true, $token, $scope > 0 ? $scope : null, date('c'));

        return self::settings();
    }

    public static function accepts(string $given): bool
    {
        $s = self::settings();

        return $s['enabled'] && $s['token'] !== '' && hash_equals($s['token'], $given);
    }

    /** Doctor id encoded in a valid token, or null for site-wide. */
    public static function doctorIdFromToken(string $token): ?int
    {
        if (!self::accepts($token)) {
            return null;
        }
        $s = self::settings();

        return $s['doctor_id'];
    }

    private static function store(bool $enabled, string $token, $doctorId, string $createdAt): void
    {
        $payload = json_encode([
            'enabled' => $enabled,
            'token' => $token === '' ? '' : Crypt::encryptString($token),
            'doctor_id' => $doctorId,
            'created_at' => $createdAt,
        ], JSON_UNESCAPED_UNICODE);

        if (get_option(self::OPTION, false) === false) {
            add_option(self::OPTION, $payload);
        } else {
            update_option(self::OPTION, $payload);
        }
    }

    private static function decrypt($value): string
    {
        if (!is_string($value) || $value === '') {
            return '';
        }
        // Prefer Crypt; fall back to plaintext (local seed / older rows).
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }
}
