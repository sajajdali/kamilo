<?php

namespace App\Console\Commands;

use App\Support\VoipIntegration;
use Illuminate\Console\Command;

class VoipTokenCommand extends Command
{
    protected $signature = 'voip:token {--doctor= : Optional doctor user id to scope appointments} {--disable : Turn VoIP integration off}';
    protected $description = 'Generate or disable the VoIP Bearer token for the call-center panel';

    public function handle()
    {
        if ($this->option('disable')) {
            VoipIntegration::setEnabled(false);
            $this->info('VoIP integration disabled.');

            return 0;
        }

        $doctor = $this->option('doctor');
        $doctorId = $doctor !== null && $doctor !== '' ? (int) $doctor : null;
        $s = VoipIntegration::generate($doctorId);

        $this->info('VoIP integration enabled.');
        $this->line('Token: ' . $s['token']);
        $this->line('Doctor scope: ' . ($s['doctor_id'] ? (string) $s['doctor_id'] : 'all'));
        $this->line('Use Authorization: Bearer <token> against /api/external-api/voip/v1/…');

        return 0;
    }
}
