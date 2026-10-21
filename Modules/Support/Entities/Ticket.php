<?php

namespace Modules\Support\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    CONST STATUS_WAITING = 0;
    CONST STATUS_ANSWERED = 1;
    CONST STATUS_CLOSED = 2;

    public static $status = [
        self::STATUS_WAITING => 'در انتظار',
        self::STATUS_ANSWERED => 'پاسخ داده شده',
        self::STATUS_CLOSED => 'بسته شده',
    ];
    
    // Settings
    CONST SETTING = [
        'SETTING_EMAIL' => 'info@your-domain.com',
        'SETTING_SEND_SMS' => 1,
        'SETTING_SEND_EMAIL' => 1,
        'SETTING_COST' => [
            '2' => '50',
            '8' => '100',
            '14' => '10000',
        ],
    ];
    
    
    public $timestamps = true;
    
    protected $table = 'tickets';
    
    protected $casts = [
        'content' => 'json',
    ];
    
    protected $fillable = [
        'user_id', 'adviser_id', 'part_id', 'title', 'code', 'status'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(TicketDetail::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adviser()
    {
        return $this->belongsTo(User::class, 'adviser_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function part()
    {
        return $this->belongsTo(Part::class);
    }
    
    /**
     * @param int $ticket_id
     * @return bool|string
     */
    public static function make_code($ticket_id = 0)
    {
        if ($ticket_id == 0) {
            return false;
        } else {
            $code = $ticket_id;
            for ($i = 0; $i < 6; $i++) {
                $code .= rand(1, 9);
            }
            if ($ticket = Ticket::find($ticket_id)) {
                $ticket->code = $code;
                $ticket->save();
            }
            return $code;
        }
    }
    
    /**
     * @param $userId
     * @return mixed
     */
    public static function filterByUser($userId)
    {
        return self::where('user_id', $userId);
    }
}
