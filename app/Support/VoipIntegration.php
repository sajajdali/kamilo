<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/**
 * «اتصال به سامانه ویپ» + inbound Bearer API for the call-center panel.
 * Stored in options as voip_integration.
 *
 * Connection fields (admin settings): enabled, panel_url, username, password.
 * Inbound API: token (+ optional doctor_id scope). Token format: km_<id>.<secret>
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
            'panel_url' => self::normalizeUrl((string) ($saved['panel_url'] ?? '')),
            'username' => (string) ($saved['username'] ?? ''),
            'password' => self::decrypt($saved['password'] ?? ''),
            'token' => self::decrypt($saved['token'] ?? ''),
            'doctor_id' => isset($saved['doctor_id']) && $saved['doctor_id'] !== '' && $saved['doctor_id'] !== null
                ? (int) $saved['doctor_id']
                : null,
            'created_at' => (string) ($saved['created_at'] ?? ''),
        ];
    }

    /** Safe for views / API (no secrets). */
    public static function publicSettings(): array
    {
        $s = self::settings();

        return [
            'enabled' => $s['enabled'],
            'panel_url' => $s['panel_url'],
            'username' => $s['username'],
            'has_password' => $s['password'] !== '',
            'has_token' => $s['token'] !== '',
            'doctor_id' => $s['doctor_id'],
            'created_at' => $s['created_at'],
        ];
    }

    public static function setEnabled(bool $enabled): array
    {
        $c = self::settings();
        self::store(
            $enabled,
            $c['panel_url'],
            $c['username'],
            $c['password'],
            $c['token'],
            $c['doctor_id'],
            $c['created_at']
        );

        return self::settings();
    }

    /**
     * Persist connection settings from admin Options form / dedicated page.
     * Empty password keeps the previous value.
     */
    public static function saveConnectionFromRequest(Request $request): array
    {
        $c = self::settings();
        $enabled = in_array((string) $request->input('voip_panel_enabled', $c['enabled'] ? '1' : '0'), ['1', 'true', 'on'], true);
        $panelUrl = self::normalizeUrl((string) $request->input('voip_panel_url', $c['panel_url']));
        $username = trim((string) $request->input('voip_panel_username', $c['username']));
        $password = (string) $request->input('voip_panel_password', '');
        if ($password === '') {
            $password = $c['password'];
        }

        self::store(
            $enabled,
            $panelUrl,
            $username,
            $password,
            $c['token'],
            $c['doctor_id'],
            $c['created_at']
        );

        return self::settings();
    }

    /**
     * New token. Pass doctor_id to scope to that doctor; omit/null for all.
     */
    public static function generate(?int $doctorId = null): array
    {
        $c = self::settings();
        $scope = $doctorId && $doctorId > 0 ? $doctorId : 0;
        $token = 'km_' . $scope . '.' . Str::random(48);
        self::store(
            true,
            $c['panel_url'],
            $c['username'],
            $c['password'],
            $token,
            $scope > 0 ? $scope : null,
            date('c')
        );

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

    private static function store(
        bool $enabled,
        string $panelUrl,
        string $username,
        string $password,
        string $token,
        $doctorId,
        string $createdAt
    ): void {
        $payload = json_encode([
            'enabled' => $enabled,
            'panel_url' => $panelUrl,
            'username' => $username,
            'password' => $password === '' ? '' : Crypt::encryptString($password),
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

    private static function normalizeUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }

        return rtrim($url, '/');
    }

    private static function decrypt($value): string
    {
        if (!is_string($value) || $value === '') {
            return '';
        }
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            return $value;
        }
    }
}
