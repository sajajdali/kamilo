<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'request_payload' => 'array',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
